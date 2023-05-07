<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import {ref} from "vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
    },
});

const user = usePage().props.user;
const department_id = ref(user.customer.city.department_id);
const cities = ref({});
const departments = ref({});

const form = useForm({
    name: user.customer.name,
    surname: user.customer.surname,
    document_type: user.customer.document_type,
    document: user.customer.document,
    email: user.email,
    phone: user.customer.phone,
    city_id: user.customer.city_id,
    address: user.customer.address
});

const isNumber = (evt) => {
    const keysAllowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
    const keyPressed = evt.key;

    if (!keysAllowed.includes(keyPressed)) {
        evt.preventDefault()
    }
}

const getCities = async () => {
    const response = await fetch(route('cities', department_id.value));
    cities.value = await response.json();
};

const getDepartments = async () => {
    const response = await fetch(route('departments'));
    departments.value = await response.json();
};

getDepartments();
getCities();
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.put(route('profile.update'), {preserveScroll: true})" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="surname" value="Surname" />

                <TextInput
                    id="surname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.surname"
                    autocomplete="surname"
                    required
                />

                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div>
                <InputLabel for="document_type" value="Document Type" />

                <TextInput
                    id="document_type"
                    type="text"
                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                    disabled
                    v-model="form.document_type"
                    autocomplete="document_type"
                    required
                />
            </div>

            <div>
                <InputLabel for="document" value="Document" />

                <TextInput
                    id="document"
                    type="text"
                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                    disabled
                    v-model="form.document"
                    autocomplete="document"
                    required
                />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="phone" value="Phone" />

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    autocomplete="phone"
                    maxlength="10"
                    v-on:keypress="isNumber($event)"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="department" value="Department" />

                <select
                    id="department"
                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                    v-model="department_id"
                    required
                    @change="getCities()"
                >
                    <option v-for="department in departments" :value="department.id">{{department.name}}</option>
                </select>
            </div>

            <div class="mt-4">
                <InputLabel for="city_id" value="City" />

                <select
                    id="city_id"
                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                    v-model.number="form.city_id"
                    required
                    autocomplete="city_id"
                >
                    <option v-for="city in cities" :value="city.id">{{city.name}}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.city_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="address" value="Address" />

                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address"
                    required
                    autocomplete="adress"
                />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
