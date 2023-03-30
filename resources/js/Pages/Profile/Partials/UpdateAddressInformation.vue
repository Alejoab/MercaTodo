<script setup>
import {useForm, usePage, Link} from "@inertiajs/vue3";
import {ref} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    departments: {
        type: Object
    },
    department_id: {
        type: Number
    },
});

const user = usePage().props.auth.user;
const department_id = ref(usePage().props.department_id);
const cities = ref({});

const form = useForm({
    city_id: user.city_id,
    address: user.address
})

const getCities = async () => {
    const response = await fetch(route('cities', department_id.value));
    cities.value = await response.json();
};

getCities();
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Address Information</h2>
        </header>

        <form @submit.prevent="form.patch(route('profile.update.address'))" class="mt-6 space-y-6">
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
