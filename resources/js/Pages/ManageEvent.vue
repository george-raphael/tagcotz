<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { currencyFormat } from "@/Utils/helper.js";
import StatView from "./Comps/StatView.vue";

defineProps({ res: Object });
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-2 lg:px-4">
                <div class="overflow-hidden py-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl">Manage Event | {{ res.eventProp.name }}</h1>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-6 border-b pb-5">
                        <div class="bg-white px-4 py-3 rounded-md">
                            <p>Total Amount</p>
                            <p class="text-3xl mt-1 font-bold ">{{ currencyFormat(res.totalAmount ?? 0) }} TZS</p>
                        </div>
                        <div class="bg-white px-4 py-3 rounded-md">
                            <p>Paid Amount</p>
                            <p class="text-3xl mt-1 font-bold ">{{ currencyFormat(res.paidAmount ?? 0) }} TZS</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <StatView :res="res" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
