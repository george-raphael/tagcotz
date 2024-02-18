<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted, ref } from "vue";
const isLoading = ref(null);

const regions = ref([]);
const districts = ref([]);

const form = useForm({
  title: "",
  first_name: "",
  last_name: "",
  phone_number: "",
  region_id: "",
  district_id: "",
  institution: "",
  email: "",
  password: "",
  password_confirmation: "",
  terms: false,
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};

const getRegions = () => {
  isLoading.value = true;
  axios.get(route("regions")).then(({ data }) => {
    regions.value = data;
    isLoading.value = false;
  });
};

const getDistricts = () => {
  isLoading.value = true;
  axios
    .get(route("region.districts", { region: form.region_id }))
    .then(({ data }) => {
      districts.value = data;
      isLoading.value = false;
    });
};

onMounted(() => {
  getRegions();
});
</script>

<template>
  <Head title="Register" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <form @submit.prevent="submit" class="grid grid-cols-2 gap-3">
      <div class="mt-4">
        <InputLabel for="title" class="dark:text-white">Title</InputLabel>
        <select
          id="title"
          v-model="form.title"
          class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
          placeholder="1112267"
        >
          <option value="Ms.">Ms.</option>
          <option value="Mr.">Mr.</option>
          <option value="Mrs.">Mrs.</option>
          <option value="Miss.">Miss.</option>
          <option value="Rev.">Rev.</option>
          <option value="Dr.">Dr.</option>
          <option value="Prof.">Prof.</option>
        </select>
        <InputError :message="form.errors.title" />
      </div>
      <div class="mt-4">
        <InputLabel for="first_name" class="dark:text-white"
          >First Name</InputLabel
        >
        <TextInput
          id="first_name"
          v-model="form.first_name"
          type="text"
          class="w-full"
          placeholder="First name"
        />
        <InputError :message="form.errors.first_name" />
      </div>
      <div class="mt-4">
        <InputLabel for="last_name" class="dark:text-white"
          >Last Name</InputLabel
        >
        <TextInput
          id="last_name"
          v-model="form.last_name"
          type="text"
          class="w-full"
          placeholder="Last name"
        />
        <InputError :message="form.errors.last_name" />
      </div>

      <div class="mt-4">
        <InputLabel for="phone_number" class="dark:text-white"
          >Phone Number</InputLabel
        >
        <TextInput
          id="phone_number"
          v-model="form.phone_number"
          type="text"
          class="w-full"
          placeholder="+255759123123"
        />
        <InputError :message="form.errors.phone_number" />
      </div>

      <div class="mt-4">
        <InputLabel for="institution" class="dark:text-white">
          Institution Name
        </InputLabel>
        <TextInput
          id="institution"
          v-model="form.institution"
          type="text"
          class="w-full"
          placeholder="Mbeya University"
        />
        <InputError :message="form.errors.institution" />
      </div>

      <div class="mt-4">
        <InputLabel for="region_id" class="dark:text-white">Region</InputLabel>
        <select
          id="region_id"
          @change="getDistricts"
          v-model="form.region_id"
          class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
          placeholder="First name"
        >
          <option v-for="region in regions" :key="region.id" :value="region.id">
            {{ region.name }}
          </option>
        </select>
        <InputError :message="form.errors.region_id" />
      </div>

      <div class="mt-4">
        <InputLabel for="district_id" class="dark:text-white">
          District
          <span>{{ isLoading ? "Loading..." : "" }}</span></InputLabel
        >
        <select
          id="district_id"
          v-model="form.district_id"
          class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >
          <option
            v-for="district in districts"
            :key="district.id"
            :value="district.id"
          >
            {{ district.name }}
          </option>
        </select>
        <InputError :message="form.errors.district_id" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="block w-full"
          required
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Password" />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="new-password"
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <TextInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="new-password"
        />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div
        v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
        class="mt-4"
      >
        <InputLabel for="terms">
          <div class="flex items-center">
            <Checkbox
              id="terms"
              v-model:checked="form.terms"
              name="terms"
              required
            />

            <div class="ml-2">
              I agree to the
              <a
                target="_blank"
                :href="route('terms.show')"
                class="underline text-sm text-gray-600 hover:text-gray-900"
                >Terms of Service</a
              >
              and
              <a
                target="_blank"
                :href="route('policy.show')"
                class="underline text-sm text-gray-600 hover:text-gray-900"
                >Privacy Policy</a
              >
            </div>
          </div>
          <InputError class="mt-2" :message="form.errors.terms" />
        </InputLabel>
      </div>

      <div class="col-span-2 flex items-center justify-between mt-4">
        <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 hover:text-gray-900"
        >
          Already registered?
        </Link>

        <PrimaryButton
          class="ml-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Register
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
