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
    role: {
        type: Object
    },
    document_types: {
        type: Object
    },
});

const user = usePage().props.user;

const form = useForm({
    name: user.name,
    surname: user.surname,
    document_type: user.document_type,
    document: user.document,
    email: user.email,
    phone: user.phone,
    role: usePage().props.role[0],
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information of {{ user.email }}</h2>
        </header>

        <form @submit.prevent="form.patch(route('admin.user.update', user.id))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div>
                <InputLabel for="surname" value="Surname"/>

                <TextInput
                    id="surname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.surname"
                    autocomplete="surname"
                />

                <InputError class="mt-2" :message="form.errors.surname"/>
            </div>

            <div class="mt-4">
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
                <InputLabel for="document" value="Document"/>

                <TextInput
                    id="document"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.document"
                    autocomplete="document"
                />

                <InputError class="mt-2" :message="form.errors.document"/>
            </div>

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

            <div>
                <InputLabel for="phone" value="Phone"/>

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.phone"/>
            </div>

            <div>
                <InputLabel value="Rol" class="mb-2"/>

                <div class="flex items-center mb-4">
                    <input v-model="form.role" id="roleAdministrator" type="radio" value="Administrator" name="roleAdministrator" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 ">
                    <label for="roleAdministrator" class="ml-2 text-sm font-medium">Administrator</label>
                </div>
                <div class="flex items-center">
                    <input v-model="form.role" id="roleCustomer" type="radio" value="Customer" name="roleCustomer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="roleCustomer" class="ml-2 text-sm font-medium ">Customer</label>
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
