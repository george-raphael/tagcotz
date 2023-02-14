<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { onMounted, ref } from "@vue/runtime-core";
const { attendees } = defineProps(["attendees"]);

const attendeesData = ref({ ...attendees });
const searchQuery = ref("");
const searchAttendees = _.debounce(() => {
    axios
        .get(
            route("search.attendees", { searchQuery: searchQuery.value ?? "" })
        )
        .then(({ data }) => {
            attendeesData.value = data;
        });
}, 1000);
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="
                        bg-white
                        overflow-hidden
                        shadow-xl
                        sm:rounded-lg
                        py-6
                    "
                >
                    <div>
                        <div class="px-6 sm:px-20 bg-white">
                            <div class="flex justify-between">
                                <div class="text-2xl">
                                    Walio jisajiri ({{ attendeesData.total }})
                                </div>
                                <div>
                                    <input
                                        v-model="searchQuery"
                                        @keyup="searchAttendees"
                                        type="text"
                                        placeholder="Search..."
                                    />
                                </div>
                            </div>

                            <table
                                class="
                                    border border-collapse
                                    mt-6
                                    text-gray-500
                                    w-full
                                "
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="
                                                text-left
                                                bg-slate-300
                                                border border-slate-300
                                            "
                                        >
                                            SN.
                                        </th>
                                        <th
                                            class="
                                                text-left
                                                bg-slate-300
                                                border border-slate-300
                                            "
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="
                                                text-left
                                                bg-slate-300
                                                border border-slate-300
                                            "
                                        >
                                            Phone
                                        </th>
                                        <th
                                            class="
                                                text-left
                                                bg-slate-300
                                                border border-slate-300
                                            "
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="
                                                text-left
                                                bg-slate-300
                                                border border-slate-300
                                            "
                                        >
                                            Location
                                        </th>
                                        <th
                                            class="
                                                text-left
                                                bg-slate-300
                                                border border-slate-300
                                            "
                                        >
                                            Institution
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
                                        <td class="border border-slate-300">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="border border-slate-300">
                                            {{ attendee.name }}
                                        </td>
                                        <td class="border border-slate-300">
                                            <a
                                                class="text-blue-500"
                                                :href="
                                                    'tel: ' +
                                                    attendee.phone_number
                                                "
                                                >{{ attendee.phone_number }}</a
                                            >
                                        </td>
                                        <td class="border border-slate-300">
                                            {{ attendee.email }}
                                        </td>
                                        <td class="border border-slate-300">
                                            {{ attendee.district.name }},
                                            {{ attendee.region.name }}
                                        </td>
                                        <td class="border border-slate-300">
                                            {{ attendee.institution }}
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
