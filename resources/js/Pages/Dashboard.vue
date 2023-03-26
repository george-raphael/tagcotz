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

const { all,verified, unverified, invalid } = defineProps([
    "all",
    "verified",
    "unverified",
    "invalid",
]);
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-2 lg:px-4">
                <div class="overflow-hidden py-6">
                    <div>
                        <div class="px-4 sm:px-12">
                            <div>
                                <h1 class="text-3xl">Dashboard</h1>
                            </div>
                            <div class="mt-3 grid grid-cols-3 sm:grid-cols-6 md:grid-cols-9 lg:grid-cols-12 gap-3">
                                <div
                                    class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-blue-500"
                                >
                                    <div class="">
                                        <h1 class="">All</h1>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-7xl">{{
                                            all
                                        }}</span>
                                    </div>
                                    <div class="text-right">
                                        <a :href="route('attendances',{status:'all'})" class="text-blue-500 hover:underline cursor-pointer"
                                            >View</a
                                        >
                                    </div>
                                </div>
                                <div
                                    class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-green-500"
                                >
                                    <div class="">
                                        <h1 class="">Verified</h1>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-7xl">{{
                                            verified
                                        }}</span>
                                    </div>
                                    <div class="text-right">
                                        <a :href="route('attendances',{status:'verified'})" class="text-blue-500 hover:underline cursor-pointer"
                                            >View</a
                                        >
                                    </div>
                                </div>
                                <div
                                    class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-yellow-500"
                                >
                                    <div class="">
                                        <h1 class="">Unverified</h1>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-7xl">{{
                                            unverified
                                        }}</span>
                                    </div>
                                    <div class="text-right">
                                        <a :href="route('attendances',{status:'unverified'})" class="text-blue-500 hover:underline cursor-pointer"
                                            >View</a
                                        >
                                    </div>
                                </div>
                                <div
                                    class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-red-500"
                                >
                                    <div class="">
                                        <h1 class="">Invalid</h1>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-7xl">{{
                                            invalid
                                        }}</span>
                                    </div>
                                    <div class="text-right">
                                        <a :href="route('attendances',{status:'invalid'})" class="text-blue-500 hover:underline cursor-pointer"
                                            >View</a
                                        >
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
