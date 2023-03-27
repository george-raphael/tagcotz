<template>
    <div v-if="items.links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in items.links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class="btn-pagination-null"
                    v-html="link.label"
                />
                <Link
                    v-else
                    :key="`link-${key}`"
                    class="bg-blue-800 mb-1 mr-1 px-4 py-3 text-sm
                        hover:cursor-pointer
                        hover:bg-blue-800 hover:text-white border
                        focus:border-blue-800 rounded
                        active:bg-blue-500 text-white"
                    :class="{
                        'bg-blue-400 text-white font-bold':
                            link.active,
                    }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
        <!-- <div class="flex sm:hidden space-x-9 -mb-1">
            Previous
            <div
                v-if="items.links[1].url === null"
                class="btn-pagination-null"
                v-html="items.links[1].label"
            ></div>
            <Link
                v-else
                key="link-prev"
                class="btn-pagination"
                :href="items.links[1].url"
                v-html="'&laquo; Previous'"
            />

            Next
            <div
                v-if="items.links[items.links.length - 2].url === null"
                key="prev"
                class="btn-pagination-null"
                v-html="items.links[items.links.length - 2].label"
            ></div>
            <Link
                v-else
                key="link-prev"
                class="btn-pagination"
                :href="items.links[items.links.length - 2].url"
                v-html="'&raquo; Next'"
            />
        </div> -->
    </div>
</template>

<script setup>
const { items } = defineProps(["items"]);
</script>
