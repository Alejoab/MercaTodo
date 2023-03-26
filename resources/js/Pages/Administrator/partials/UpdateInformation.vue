<script setup>
import {useForm, usePage, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    user: {
        type: Object
    },
    role: {
        type: String
    },
    documentTypes: {
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
    role: !! usePage().props.role[0],
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
                    <option v-for="type in documentTypes" :value="type">{{ type }}</option>
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
            </div>

            <div>
                <InputLabel for="email" value="Email"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
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
                    required
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.phone"/>
            </div>

            <div>
                <h1 class="mb-2">Role</h1>
                <div class="flex items-center">
                    <Checkbox id="Administrator" value="Administrator" :checked="!!form.role" v-model="form.role"/>
                    <label class="ml-3 font-medium text-gray-700" for="Administrator">Administrator</label>
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
