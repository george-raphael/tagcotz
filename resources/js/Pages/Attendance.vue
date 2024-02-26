<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Pagination from "@/Components/Pagination.vue";

import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import { onMounted, ref } from "@vue/runtime-core";
import { router } from "@inertiajs/vue3";
const { res } = defineProps(["res"]);
const attendeesData = ref({ ...res.attendeesData });
const activeAttendee = ref(null);
const activeIndex = ref(null);
const isLoading = ref(false);
const isOpen = ref(false);
function closeModal() {
  isOpen.value = false;
}
const searchQuery = ref("");
function openModalForAttendee(params, index) {
  activeAttendee.value = params;
  activeIndex.value = index;
  isOpen.value = true;
}
function updateAttendeeStatus(status) {
  isOpen.value = false;
  router.post(
    route("update.usajili", { attendance: activeAttendee.value.id }),
    { status: status },
    {
      preserveScroll: true,
    }
  );
}

function deleteAttendance() {
  isOpen.value = false;
  axios
    .post(route("delete.usajili", { attendance: activeAttendee.value.id }))
    .then(() => {
      attendeesData.value.data?.splice(activeIndex.value, 1);
      attendeesData.value.total = attendeesData.value.total - 1;
    });
}
const searchAttendees = _.debounce(() => {
  isLoading.value = true;
  axios
    .get(
      route("search.attendees", {
        searchQuery: searchQuery.value ?? "",
        status: res.status,
        event_id: res.eventProp.id,
      })
    )
    .then(({ data }) => {
      isLoading.value = false;
      attendeesData.value = data;
    });
}, 1000);

function capitalize(string) {
  return string?.charAt(0)?.toUpperCase() + string?.slice(1);
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
                  <b>Manage</b>: {{ activeAttendee.user.name }}
                </DialogTitle>
                <div class="my-12 flex items-center justify-center text-sm">
                  <table
                    class="border border-collapse mt-6 text-gray-500 min-w-full"
                  >
                    <thead>
                      <tr>
                        <th
                          class="table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                        >
                          Sn.
                        </th>
                        <th
                          class="table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                        >
                          Phone
                        </th>
                        <th
                          class="table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                        >
                          Reference ID
                        </th>
                        <th
                          class="table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                        >
                          Status
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(pa, index) in activeAttendee.payment_attempts"
                        :key="pa.id"
                      >
                        <td class="border px-2 border-slate-300">
                          {{ index + 1 }}
                        </td>
                        <td class="border px-2 border-slate-300">
                          {{ pa.payment_phone_number }}
                        </td>
                        <td class="border px-2 border-slate-300">
                          {{ pa.transaction_status_number }}
                        </td>
                        <td
                          class="border capitalize px-2 border-slate-300 py-1"
                        >
                          <span
                            :class="
                              pa.status == 'paid'
                                ? 'bg-green-100 text-green-700 shadow-sm border border-green-200 px-2 py-.5 rounded-xl'
                                : 'bg-yellow-100 text-yellow-700 shadow-sm border border-yellow-200 px-2 py-.5 rounded-xl'
                            "
                          >
                            {{ pa.status }}</span
                          >
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div
                  class="mt-4 flex flex-col items-center justify-center space-x-2"
                >
                  <div class="flex items-center justify-center space-x-2">
                    <button
                      @click="deleteAttendance"
                      class="inline-flex justify-center rounded-md border border-transparent bg-red-100 px-6 py-2 text-lg font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                    >
                      Delete
                    </button>
                    <button
                      type="button"
                      class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-6 py-2 text-lg font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
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
                      @click="updateAttendeeStatus('verified')"
                    >
                      Verify
                    </button>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-2 lg:px-4">
        <div>
          <a :href="route('dashboard')" class="text-link">Dashboard</a>
          /
          <a
            :href="route('event.view', { event: res.eventProp.id })"
            class="text-link font-bold"
            >{{ res.eventProp.name }}</a
          >
          /
          <span>{{ capitalize(res.status) }}</span>
        </div>
        <div class="mt-3 bg-white overflow-hidden shadow-xl sm:rounded-lg py-6">
          <div>
            <div class="px-4 sm:px-12 bg-white">
              <div class="flex sm:flex-row flex-col justify-between">
                <div class="text-2xl">
                  {{ capitalize(res.status) }} ({{ attendeesData.total }})
                </div>
                <div
                  class="flex sm:flex-row flex-col-reverse space-x-2 items-center"
                >
                  <div>
                    <input
                      class="mt-2"
                      v-model="searchQuery"
                      @keyup="searchAttendees"
                      type="text"
                      placeholder="Search..."
                    />
                  </div>
                  <div class="flex flex-row space-x-2 mt-2 items-center">
                    <a
                      :href="
                        route('dashboard.print.ids', {
                          event: res.eventProp.id,
                        })
                      "
                      target="_blank"
                      class="px-4 py-2 bg-blue-800 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition flex items-center justify-center rounded-none"
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
                    <a
                      :href="
                        route('dashboard.export', {
                          searchQuery: searchQuery,
                          status: res.status,
                        })
                      "
                      target="_blank"
                      class="px-4 py-2 bg-blue-800 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition flex items-center justify-center rounded-none"
                    >
                      <svg
                        class="w-5 h-5 text-gray-100"
                        fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg"
                        stroke-width="1.5"
                        viewBox="0 0 384 512"
                      >
                        <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                          d="M48 448V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                        />
                      </svg>

                      <span class="ml-2 text-lg">Excel</span>
                    </a>
                  </div>
                </div>
              </div>
              <div v-if="!isLoading">
                <table
                  class="border border-collapse mt-6 text-gray-500 min-w-full"
                >
                  <thead>
                    <tr>
                      <th
                        class="text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        SN.
                      </th>
                      <th
                        class="text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Name
                      </th>
                      <th
                        class="hidden lg:table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Contact
                      </th>

                      <th
                        class="hidden lg:table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Institution
                      </th>
                      <th
                        class="hidden lg:table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Payment Method
                      </th>
                      <!-- <th
                        class="hidden lg:table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Payment Status
                      </th> -->
                      <th
                        class="hidden lg:table-cell text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Attendance Status
                      </th>
                      <th
                        class="text-left bg-slate-300 border border-slate-300 px-2 py-3"
                      >
                        Action
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(attendee, index) in attendeesData.data"
                      :key="attendee.id"
                    >
                      <td class="border px-2 border-slate-300">
                        {{ res.attendeesData?.from + index }}
                      </td>
                      <td class="border px-2 border-slate-300">
                        <p class="">
                          <span class="text-lg font-bold lg:text-normal">{{
                            attendee.user?.name
                          }}</span>
                          <span class="text-sm block">
                            {{ attendee.user?.district?.name }},
                            {{ attendee.user?.region?.name }}</span
                          >
                        </p>
                        <a
                          class="lg:hidden text-blue-500 block"
                          :href="'tel: ' + attendee.user?.phone_number"
                          >{{ attendee.user?.phone_number }}
                        </a>
                        <span class="lg:hidden">
                          {{ attendee.user?.institution }}</span
                        >
                      </td>
                      <td
                        class="hidden lg:table-cell border px-2 border-slate-300"
                      >
                        <p>
                          <a
                            class="text-blue-500 block"
                            :href="'tel: ' + attendee.user?.phone_number"
                            >{{ attendee.user?.phone_number }}
                          </a>
                          <span class="block">{{ attendee.user?.email }}</span>
                        </p>
                      </td>

                      <td
                        class="hidden lg:table-cell border px-2 border-slate-300"
                      >
                        {{ attendee.user?.institution }}
                      </td>
                      <td
                        class="hidden lg:table-cell border px-2 border-slate-300 text-center"
                      >
                        {{ attendee.payment_method }}
                      </td>
                      <!-- <td
                        class="hidden lg:table-cell border px-2 border-slate-300"
                      >
                        {{ attendee.payment_attempts?.filter((pa)=>
                          pa.status == 'paid'
                        )?.[0]?.status }}
                      </td> -->
                      <td
                        class="hidden lg:table-cell border px-2 border-slate-300 text-center"
                      >
                        <span
                          v-if="attendee.status == 'unverified'"
                          class="px-1 block py-1 rounded-full text-xs m-2 bg-red-100 text-red-900"
                        >
                          {{ attendee.status }}
                        </span>
                        <span
                          v-else-if="attendee.status == 'verified'"
                          class="px-1 block py-1 rounded-full text-xs m-2 bg-green-100 text-green-900"
                        >
                          {{ attendee.status }}
                        </span>
                        <span
                          v-else-if="attendee.status == 'invalid'"
                          class="px-1 block py-1 rounded-full text-xs m-2 bg-sky-100 text-sky-900"
                        >
                          {{ attendee.status }}
                        </span>
                      </td>
                      <td class="border px-2 border-slate-300 space-y-3">
                        <button
                          @click.prevent="openModalForAttendee(attendee, index)"
                          class="block underline text-blue-700 mx-2"
                        >
                          View
                        </button>
                        <Link
                          class="block underline text-blue-500"
                          :href="
                            route('dashboard', {
                              orderId: attendee.order_number,
                            })
                          "
                          >Refresh</Link
                        >
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="mt-3 min-w-full" v-if="attendeesData.data">
                  <Pagination :items="attendeesData" />
                </div>
              </div>
              <div v-else class="w-full">
                <img
                  class="mx-auto border w-12 bg-blue-400 rounded-full"
                  src="/img/loader.svg"
                  alt=""
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
