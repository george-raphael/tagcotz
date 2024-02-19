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
                            class="w-full max-w-2xl transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-2xl text-center font-medium leading-6 text-gray-900">
                                <b class="uppercase">Add Event</b>
                            </DialogTitle>
                            <div>
                                <form @submit.prevent="saveEvent" class="grid grid-cols-1 gap-4 mt-4 pt-4 border-t">
                                    <div class="">
                                        <InputLabel for="name" class="dark:text-white">Event Name</InputLabel>
                                        <TextInput id="name" v-model="form.name" type="text" class="w-full"
                                            placeholder="Enter event name" />
                                        <InputError :message="form.errors.name" />
                                    </div>
                                    <div class="">
                                        <InputLabel for="amount" class="dark:text-white">Joining Amount</InputLabel>
                                        <TextInput id="amount" v-model="form.amount" type="number" class="w-full"
                                            placeholder="300000" />
                                        <InputError :message="form.errors.amount" />
                                    </div>
                                    <div class="">
                                        <InputLabel for="year" class="dark:text-white">Year</InputLabel>
                                        <TextInput id="year" v-model="form.year" type="text" class="w-full"
                                            placeholder="2024" />
                                        <InputError :message="form.errors.year" />
                                    </div>
                                    <div class="">
                                        <InputLabel for="event_date" class="dark:text-white">Event Date</InputLabel>
                                        <TextInput id="event_date" v-model="form.event_date" type="date" class="w-full"
                                            placeholder="" />
                                        <InputError :message="form.errors.event_date" />
                                    </div>
                                    <div>
                                        <InputLabel for="status" class="dark:text-white">Event Status</InputLabel>
                                        <select id="status" v-model="form.status"
                                            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                            <option value="1">Active</option>
                                            <option value="0">Outdated</option>
                                        </select>
                                        <InputError :message="form.errors.status" />
                                    </div>
                                    <div class="mt-4 mb-3 flex items-center justify-end space-x-5">
                                        <b class="cursor-pointer" @click.prevent="isOpen = false">Close</b>
                                        <PrimaryButton :disabled="form.processing" type="submit">
                                            <b> {{ form.processing ? 'Saving...' : 'Save Event' }}</b>
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

                <a @click.prevent="isOpen = true"
                    class="px-4 py-2 cursor-pointer bg-blue-800 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition flex items-center justify-center rounded-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-gray-100">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>

                    <span class="ml-2 text-lg">Create Event</span>
                </a>
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
                            Status
                        </th>
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Attendees
                        </th>
                        <th class="text-left bg-slate-300 border border-slate-50 py-3 px-2">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(event, index) in res.events" :key="event.id">
                        <td class="border px-2 border-slate-300">
                            {{ 1 + index }}
                        </td>
                        <td class="border px-2 border-slate-300">
                            <p>{{ event.name }}</p>
                        </td>
                        <td class="border px-2 border-slate-300">
                            <p class="font-bold">
                                {{ currencyFormat(event.amount) }} TZS
                            </p>
                        </td>
                        <td class="border px-2 border-slate-300">
                            {{ event.year }}
                        </td>
                        <td class="border px-2 border-slate-300">
                            {{ event.formattedEventDate }}
                        </td>
                        <td class="border px-2 border-slate-300 ">
                            <span v-if="event.statusLabel ==
                                'Outdated'
                                " class="px-2 inline-block py-1 rounded-full text-xs m-2 bg-red-100 text-red-900">
                                {{ event.statusLabel }}
                            </span>
                            <span v-else-if="event.statusLabel ==
                                'Active'
                                " class="px-2 inline-block py-1 rounded-full text-xs m-2 bg-green-100 text-green-900">
                                {{ event.statusLabel }}
                            </span>
                        </td>
                        <td class="border px-2 border-slate-300">
                            {{ currencyFormat(event.attendeesCount) }}
                        </td>
                        <td class="px-2 py-1  flex items-center gap-1">
                            <Link :href="route('event.view', { event: event.id })"
                                class="underline text-blue-700 m-1">
                            View Event
                            </Link>
                            <div class="flex items-center gap-0.5">
                                <button @click.prevent="editEvent(event)" class="underline text-green-700 m-1">
                                    Edit
                                </button>
                                <button @click.prevent="destroyEvent(event)" class="underline text-red-700 m-1">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!res.events.length">
                        <td colspan="7">
                            <p class="text-xl text-center text-slate-500 py-8">No events available</p>
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

import { router, useForm } from "@inertiajs/vue3";
import { ref } from "vue"

defineProps({
    res: Object
})
const isOpen = ref(false);

const form = useForm({
    name: "",
    amount: "",
    status: "Active",
    year: "",
    event_date: "",
    id: null,
});

function closeModal() {
    isOpen.value = false;
}

function saveEvent() {
    form.post(form.id ? route('event.update', { event: form.id }) : route('event.store'), {
        onFinish: () => {
            form.reset();
            closeModal();
        }
    })
}

function editEvent(record) {

    arrangeFormData(record);

    setTimeout(() => {
        isOpen.value = true;
    }, 500)
}

function destroyEvent(record) {
    if (confirm('Are sure you want to delete event?')) {
        router.delete(route('event.destroy', { event: record.id }), {
            onFinish: () => {
                form.reset();
                isOpen.value = false;
            }
        })
    }
}

function arrangeFormData(obj) {
    const array = Object.keys(obj);
    for (let index = 0; index < array.length; index++) {
        const element = array[index];
        form[element] = obj[element]
    }
}

</script>