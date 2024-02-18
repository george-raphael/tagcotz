<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PrimaryButton from "../Components/PrimaryButton.vue";
import GuestLayout from "../Layouts/GuestLayout.vue";
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <GuestLayout>
        <Head title="Welcome" />

        <div
            class="
                relative
                flex
                items-top
                justify-center
                bg-gray-100
                dark:bg-gray-900
                sm:items-center sm:pt-0
            "
        >
            <div v-if="canLogin" class="fixed top-0 right-0 px-6 py-4 sm:block">
                <Link
                    v-if="$page.props.user"
                    :href="route('dashboard')"
                    class="text-sm text-gray-700 dark:text-gray-500 underline"
                    >Dashboard</Link
                >

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="
                            text-sm text-gray-700
                            dark:text-gray-500
                            underline
                        "
                        >Log in</Link
                    >

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="
                            ml-4
                            text-sm text-gray-700
                            dark:text-gray-500
                            underline
                        "
                        >Register</Link
                    >
                </template>
            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="my-4">
                    <img class="w-24 h-24 mx-auto" src="/img/logo.png" alt="" />
                </div>

                <div
                    class="
                        flex flex-col
                        items-center
                        mt-8
                        bg-white
                        dark:bg-gray-800
                        overflow-hidden
                        shadow
                        sm:rounded-lg
                        py-6
                        px-6
                    "
                >
                    <p class="dark:text-white">Mfumo wa kujisajili</p>
                    <PrimaryButton class="mt-3">
                        <Link
                            :href="route('register')"
                            class="flex items-center justify-center"
                        >
                            <span >Jisajili</span>
                            &nbsp;
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="w-5 h-5"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </Link>
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
