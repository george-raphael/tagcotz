<template>
    <div class="mt-3 grid grid-cols-3 sm:grid-cols-6 md:grid-cols-9 lg:grid-cols-12 gap-3">
        <div class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-blue-500">
            <div class="">
                <h1 class="">All</h1>
            </div>
            <div class="text-center">
                <span class="text-7xl">{{
                    res.all
                }}</span>
            </div>
            <div class="text-right">
                <a :href="route('attendances', { event: res.eventProp.id, status: 'all' })"
                    class="text-blue-500 hover:underline cursor-pointer">View</a>
            </div>
        </div>
        <div class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-green-500">
            <div class="">
                <h1 class="">Verified</h1>
            </div>
            <div class="text-center">
                <span class="text-7xl">{{
                    res.verified
                }}</span>
            </div>
            <div class="text-right">
                <a :href="route('attendances', { event: res.eventProp.id, status: 'verified' })"
                    class="text-blue-500 hover:underline cursor-pointer">View</a>
            </div>
        </div>
        <div class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-yellow-500">
            <div class="">
                <h1 class="">Unverified</h1>
            </div>
            <div class="text-center">
                <span class="text-7xl">{{
                    res.unverified
                }}</span>
            </div>
            <div class="text-right">
                <a :href="route('attendances', { event: res.eventProp.id, status: 'unverified' })"
                    class="text-blue-500 hover:underline cursor-pointer">View</a>
            </div>
        </div>
        <div class="col-span-3 bg-white shadow-sm rounded-lg p-3 border-l-8 border-red-500">
            <div class="">
                <h1 class="">Invalid</h1>
            </div>
            <div class="text-center">
                <span class="text-7xl">{{
                    res.invalid
                }}</span>
            </div>
            <div class="text-right">
                <a :href="route('attendances', { event: res.eventProp.id, status: 'invalid' })"
                    class="text-blue-500 hover:underline cursor-pointer">View</a>
            </div>
        </div>

    </div>
</template>
<script setup>
defineProps({
    res: Object
})
</script>