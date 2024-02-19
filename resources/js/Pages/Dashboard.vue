<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import EventMgt from "./Comps/EventMgt.vue";
import JisajiliMgt from "./Comps/JisajiliMgt.vue";

defineProps({ res: Object });
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-2 lg:px-4">
                <div class="overflow-hidden py-6">
                    <EventMgt v-if="$page.props.user.type == 1" :res="res" />
                    <JisajiliMgt v-else :res="res" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
