<script setup>
import {useForm, usePage, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

defineProps({
    user: {
        type: Object
    },
    roles: {
        type: Object
    }
});

const user = usePage().props.user;
const roles = usePage().props.roles;

const form = useForm({
    email: user.email,
    role: user.roles[0].name,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Change Role</h2>
        </header>

        <form @submit.prevent="form.put(route('admin.user.update', user.id), {preserveScroll: true})" class="mt-6 space-y-6">
            <div>
                <InputLabel for="email" value="Email"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                    v-model="form.email"
                    disabled
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="pt-5">
                <div v-for="role in roles" class="flex items-center mb-4">
                    <input v-model="form.role" :id="role" type="radio" :value="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 ">
                    <label :for="role" class="ml-2 text-sm font-medium">{{role}}</label>
                </div>

                <InputError class="mt-2" :message="form.errors.roles" />
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
