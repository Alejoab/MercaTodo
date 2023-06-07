<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {ref} from "vue";

const props = defineProps({
    document_types: {
        type: Object
    },
})

const form = useForm({
    name: '',
    surname: '',
    document: '',
    document_type: '',
    email: '',
    phone: '',
    address: '',
    city_id: 0,
    password: '',
    password_confirmation: '',
    terms: false,
});

const department_id = ref(0);
const cities = ref({});
const departments = ref({});

const getCities = async () => {
    const response = await fetch(route('cities', department_id.value));
    cities.value = await response.json();
};

const getDepartments = async () => {
    const response = await fetch(route('departments'));
    departments.value = await response.json();
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const isNumber = (evt) => {
    const keysAllowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
    const keyPressed = evt.key;

    if (!keysAllowed.includes(keyPressed)) {
        evt.preventDefault()
    }
}

getDepartments();
</script>

<template>
    <GuestLayout>
        <Head><title>Registration</title></Head>

        <form @submit.prevent="submit">
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

            <div class="mt-4">
                <InputLabel for="surname" value="Surname" />

                <TextInput
                    id="surname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.surname"
                    required
                    autocomplete="surname"
                />

                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div class="mt-4">
                <InputLabel for="document_type" value="Document Type" />

                <select
                    id="document_type"
                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                    v-model="form.document_type"
                    required
                    autocomplete="document_type"
                >
                    <option v-for="type in document_types" :value="type">{{type}}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.document_type" />
            </div>

            <div class="mt-4">
                <InputLabel for="document" value="Document" />

                <TextInput
                    id="document"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.document"
                    required
                    autocomplete="document"
                    maxlength="11"
                    v-on:keypress="isNumber($event)"
                />

                <InputError class="mt-2" :message="form.errors.document" />
            </div>

            <div class="mt-4">
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

            <div class="mt-4">
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

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4 justify-between">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
                <InputError class="mt-5" :message="form.errors.app" />
        </form>
    </GuestLayout>
</template>
