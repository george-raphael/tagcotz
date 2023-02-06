<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import GuestLayout from "../../Layouts/GuestLayout.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    regions: Array,
});

const districts = ref(null);
const isLoading = ref(null);

const usajiriForm = useForm({
    first_name: "",
    last_name: "",
    phone_number: "",
    region_id: "",
    district_id: "",
});

const getDistricts = () => {
    isLoading.value = true;
    axios
        .get(route("region.districts", { region: usajiriForm.region_id }))
        .then(({ data }) => {
            districts.value = data;
            isLoading.value = false;
        });
};

const storeUsajiri = () => {
    usajiriForm.post(route("store.usajiri"));
};
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
            min-h-screen
            bg-gray-100
            dark:bg-gray-900
            sm:items-center sm:pt-0
        "
    >
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

        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="my-4">
                <img class="w-24 h-24 mx-auto" src="/img/logo.png" alt="" />
            </div>

            <div
                class="
                    flex flex-col
                    bg-white
                    dark:bg-gray-800
                    overflow-hidden
                    shadow
                    sm:rounded-lg
                    py-6
                    px-3
                "
            >
                <p class="text-center text-xl">
                    Jaza taarifa zako ili ujisajiri
                </p>
                <hr />

                <div class="mt-3">
                    <label for="first_name">First Name</label>
                    <input
                        id="first_name"
                        v-model="usajiriForm.first_name"
                        type="text"
                        class="w-full"
                        placeholder="First name"
                    />
                </div>
                <div class="mt-3">
                    <label for="last_name">Last Name</label>
                    <input
                        id="last_name"
                        v-model="usajiriForm.last_name"
                        type="text"
                        class="w-full"
                        placeholder="Last name"
                    />
                </div>

                <div class="mt-3">
                    <label for="phone_number">Phone Number</label>
                    <input
                    id="phone_number"
                        v-model="usajiriForm.phone_number"
                        type="text"
                        class="w-full"
                        placeholder="+255759123123"
                    />
                    <div class="mt-3 text-red-500">
                        {{ usajiriForm.errors.phone_number }}
                    </div>
                </div>

                <div class="mt-3">
                    <label for="region_id">Region</label>
                    <select
                    id="region_id"
                        @change="getDistricts"
                        v-model="usajiriForm.region_id"
                        class="w-full"
                        placeholder="First name"
                    >
                        <option
                            v-for="region in regions"
                            :key="region.id"
                            :value="region.id"
                        >
                            {{ region.name }}
                        </option>
                    </select>
                </div>

                <div class="mt-3">
                    <label for="district_id">
                        District
                        <span>{{ isLoading ? "Loading..." : "" }}</span></label
                    >
                    <select
                    id="district_id"
                        v-model="usajiriForm.district_id"
                        class="w-full"
                    >
                        <option
                            v-for="district in districts"
                            :key="district.id"
                            :value="district.id"
                        >
                            {{ district.name }}
                        </option>
                    </select>
                </div>

                <PrimaryButton
                    @click="storeUsajiri"
                    class="mt-3 flex items-center justify-center"
                >
                    <span>Hifazi</span>
                </PrimaryButton>
            </div>
        </div>
    </div>
    </GuestLayout>

</template>
