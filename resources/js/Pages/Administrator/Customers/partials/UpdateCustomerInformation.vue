<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

defineProps({
    user: {
        type: Object
    },
    document_types: {
        type: Object
    }
})

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
            <h2 class="text-lg font-medium text-gray-900">Customer Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update the customer's profile information
            </p>
        </header>

        <form @submit.prevent="form.put(route('admin.customer.update', user.id))" class="mt-6 space-y-6 max-w-xl">
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
                />

                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div>
                <InputLabel for="document_type" value="Document Type"/>

                <select
                    id="document_type"
                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                    v-model="form.document_type"
                    required
                    autocomplete="document_type"
                >
                    <option v-for="type in document_types" :value="type">{{ type }}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.document_type"/>
            </div>

            <div>
                <InputLabel for="document" value="Document" />

                <TextInput
                    id="document"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.document"
                    autocomplete="document"
                    maxlength="11"
                    v-on:keypress="isNumber($event)"
                />

                <InputError class="mt-2" :message="form.errors.document"/>
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                    disabled
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

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
