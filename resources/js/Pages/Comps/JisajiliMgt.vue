<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="w-full max-w-xl transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-2xl text-center font-medium leading-6 text-gray-900">
                                <b class="uppercase">Fanya Malipo</b>
                            </DialogTitle>
                            <div>
                                <form @submit.prevent="handleKulipia" class="grid grid-cols-1 gap-4 mt-4 pt-4 border-t">
                                    <div class="">
                                        <InputLabel for="phone_number" class="dark:text-white text-xl text-center w-full">
                                            Weka namba ya simu utakayotumia kufanya malipo:</InputLabel>
                                        <TextInput id="phone_number" v-model="form.phone_number" type="text"
                                            class="w-full mt-5" placeholder="25574346XXXX" />
                                        <InputError :message="form.errors.phone_number" />
                                    </div>

                                    <div class="mt-4 mb-3 flex items-center justify-end space-x-5">
                                        <b class="cursor-pointer" @click.prevent="isOpen = false">Close</b>
                                        <PrimaryButton :disabled="form.processing" type="submit">
                                            <b> {{ form.processing ? 'Inachakata...' : 'Endelea' }}</b>
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>





    <div>
        <div class="px-4 py-5 sm:px-12 bg-white">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl">Events</h1>
                <p v-if="$page.props.success"
                    class="text-green-600 font-semibold rounded-lg px-6 py-1.5 bg-green-100 border border-green-200">{{
                        $page.props?.success }}</p>
            </div>

            <table class="border border-collapse mt-6 text-gray-500 min-w-full">
                <thead>
                    <tr class="">
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            SN.
                        </th>
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Event Name
                        </th>
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Joining Amount
                        </th>
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Event Year
                        </th>
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Event Date
                        </th>

                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="res.event">
                        <td class="border px-2 border-slate-300">
                            {{ 1 }}
                        </td>
                        <td class="border px-2 border-slate-300">
                            <p>{{ res.event.name }}</p>
                        </td>
                        <td class="border px-2 border-slate-300">
                            <p class="font-bold">
                                {{ currencyFormat(res.event.amount) }} TZS
                            </p>
                        </td>
                        <td class="border px-2 border-slate-300">
                            {{ res.event.year }}
                        </td>
                        <td class="border px-2 border-slate-300">
                            {{ res.event.event_date }}
                        </td>

                        <td class="px-2 py-1  flex items-center gap-1">
                            <Link v-if="!res.event.attendance" as="button" method="POST"
                                :href="route('event.jisajili', { event: res.event.id })"
                                class="px-2 rounded-md border-blue-700 font-bold py-2 border text-blue-800 hover:text-blue-700 hover:border-blue-600 mx-2">
                            Jisajili
                            </Link>
                            <template v-else>
                                <button v-if="res.event.attendance.status === 'unverified'" @click.prevent="handleMalipo"
                                    class="px-2 rounded-md border-blue-700 font-bold py-2 border text-blue-800 hover:text-blue-700 hover:border-blue-600 mx-2">
                                    Fanya Malipo
                                </button>
                                <span v-else class="text-green-500 ">Paid</span>
                            </template>
                        </td>
                    </tr>
                    <tr v-if="!res.event">
                        <td colspan="7">
                            <p class="text-xl text-center text-slate-500 py-8">No active event available</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { currencyFormat } from "@/Utils/helper.js";

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

import { router, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue"

const {res} = defineProps({
    res: Object
})
const isOpen = ref(false);

const form = useForm({
    phone_number: usePage().props.user.phone_number,
});

function closeModal() {
    isOpen.value = false;
}

function handleKulipia() {
    form.post(route('attendance.lipia', { attendance: res.event?.attendance.id }), {
        onFinish: () => {
            form.reset();
            closeModal();
        }
    })
}

function handleMalipo() {
    isOpen.value = true
}


</script>