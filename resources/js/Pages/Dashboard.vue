<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import { onMounted, ref } from "@vue/runtime-core";
import { router } from "@inertiajs/vue3";
const { attendees } = defineProps(["attendees"]);
const attendeesData = ref({ ...attendees });
const activeAttendee = ref(null);
const isOpen = ref(false);
const isLoading = ref(true);
function closeModal() {
    isOpen.value = false;
}
const searchQuery = ref("");
function openModalForAttendee(params) {
    activeAttendee.value = params;
    isOpen.value = true;
}
function updateAttendeeStatus(status) {
    isOpen.value = false;
    router.post(
        route("update.usajili", { attendance: activeAttendee.value.id }),
        { status: status },
        {
            onFinish: () => {
                router.get(route("dashboard"));
            },
            preserveScroll: true,
        }
    );
}
const searchAttendees = _.debounce(() => {
    axios
        .get(
            route("search.attendees", { searchQuery: searchQuery.value ?? "" })
        )
        .then(({ data }) => {
            attendeesData.value = data;
        });
}, 1000);

function imageLoading() {
    isLoading.value = false;
}
</script>

<template>
    <AppLayout title="Dashboard">
        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="text-2xl text-center font-medium leading-6 text-gray-900"
                                >
                                    <b>Paid By</b>: {{ activeAttendee.name }}
                                </DialogTitle>
                                <div class="mt-2 h-[40rem] min-w-xl relative">
                                    <img
                                        :onload="imageLoading"
                                        :src="
                                            isLoading
                                                ? '/img/tagcotz.gif'
                                                : activeAttendee.receipt
                                        "
                                        alt="Receipt image"
                                        class="h-full w-full absolute inset-0 object-center object-contain"
                                    />
                                </div>

                                <div
                                    class="mt-4 flex items-center justify-end space-x-2"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-100 px-6 py-2 text-lg font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                                        @click="closeModal"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-sky-100 px-6 py-2 text-lg font-medium text-sky-900 hover:bg-sky-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2"
                                        @click="updateAttendeeStatus('invalid')"
                                    >
                                        Not valid
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-100 px-6 py-2 text-lg font-medium text-green-900 hover:bg-green-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
                                        @click="
                                            updateAttendeeStatus('verified')
                                        "
                                    >
                                        Verify
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6"
                >
                    <div>
                        <div class="px-6 sm:px-20 bg-white">
                            <div class="flex justify-between">
                                <div class="text-2xl">
                                    Walio jisajili ({{ attendeesData.total }})
                                </div>
                                <div
                                    class="flex flex-row space-x-2 items-center"
                                >

                                    <div>
                                        <input
                                            v-model="searchQuery"
                                            @keyup="searchAttendees"
                                            type="text"
                                            placeholder="Search..."
                                        />
                                    </div>
                                    <a
                                        :href="route('dashboard.print.ids')"
                                        target="_blank"
                                        class=" px-4 py-2 bg-blue-800 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition flex items-center justify-center rounded-none"
                                        ><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"
                                            />
                                        </svg>

                                        <span class="ml-2 text-lg">Print IDS</span>
                                    </a>
                                </div>
                            </div>
                            <table
                                class="border border-collapse mt-6 text-gray-500 w-full"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            SN.
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Phone
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Location
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Institution
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="text-left bg-slate-300 border border-slate-300"
                                        >
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            attendee, index
                                        ) in attendeesData.data"
                                        :key="attendee.id"
                                    >
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            {{ index + 1 }}
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            {{ attendee.name }}
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            <a
                                                class="text-blue-500"
                                                :href="
                                                    'tel: ' +
                                                    attendee.phone_number
                                                "
                                                >{{ attendee.phone_number }}</a
                                            >
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            {{ attendee.email }}
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            {{ attendee.district.name }},
                                            {{ attendee.region.name }}
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            {{ attendee.institution }}
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300 text-center"
                                        >
                                            <span
                                                v-if="
                                                    attendee.status ==
                                                    'unverified'
                                                "
                                                class="px-1 block py-1 rounded-full text-xs m-2 bg-red-100 text-red-900"
                                            >
                                                {{ attendee.status }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    attendee.status ==
                                                    'verified'
                                                "
                                                class="px-1 block py-1 rounded-full text-xs m-2 bg-green-100 text-green-900"
                                            >
                                                {{ attendee.status }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    attendee.status == 'invalid'
                                                "
                                                class="px-1 block py-1 rounded-full text-xs m-2 bg-sky-100 text-sky-900"
                                            >
                                                {{ attendee.status }}
                                            </span>
                                        </td>
                                        <td
                                            class="border px-2 border-slate-300"
                                        >
                                            <button
                                                @click.prevent="
                                                    openModalForAttendee(
                                                        attendee
                                                    )
                                                "
                                                class="underline text-blue-700 mx-2"
                                            >
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
