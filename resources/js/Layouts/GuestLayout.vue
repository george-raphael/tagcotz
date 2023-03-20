<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link href="/">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->

                                <div
                                    v-if="canLogin"
                                    class="fixed top-0 right-0 px-6 py-4 sm:block"
                                >
                                    <Link
                                        v-if="$page.props.user"
                                        :href="route('dashboard')"
                                        class="text-sm text-gray-700 dark:text-gray-500 underline"
                                        >Dashboard</Link
                                    >

                                    <template v-else>
                                        <Link
                                            :href="route('login')"
                                            class="text-sm text-gray-700 dark:text-gray-500 underline"
                                            >Log in</Link
                                        >

                                        <Link
                                            v-if="canRegister"
                                            :href="route('register')"
                                            class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline"
                                            >Register</Link
                                        >
                                    </template>
                                </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <!-- Teams Dropdown -->
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative"></div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
                <div
                    class="flex flex-col justify-center mt-4 sm:items-center sm:justify-between"
                >
                    <div class="text-center text-sm text-gray-500 sm:text-left">
                        <div class="flex items-center justify-center">
                            Copyright &copy;
                            <a
                                href="https://tagcotz.com"
                                class="ml-1 underline"
                            >
                                TAGCO
                            </a>
                        </div>
                    </div>

                    <div
                        class="text-center text-sm text-gray-500 sm:text-right sm:ml-0"
                    >
                        Online Registration System
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
