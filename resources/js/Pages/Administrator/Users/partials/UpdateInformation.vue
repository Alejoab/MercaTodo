<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    user: {
        type: Object
    },
    roles: {
        type: Object
    },
    permissions: {
        type: Object
    }
});

const form = useForm({
    email: props.user.email,
    role: props.user.roles.map(role => role.name)[0],
    permissions: props.user.permissions.map(permission => permission.name) ?? [],
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Change Role</h2>
        </header>

        <form class="mt-6 space-y-6"
              @submit.prevent="form.put(route('admin.user.update', user.id), {preserveScroll: true})">
            <div>
                <InputLabel for="email" value="Email"/>

                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                    disabled
                    type="email"
                />

                <InputError :message="form.errors.email" class="mt-2"/>
            </div>

            <div class="pt-5 space-y-2">
                <InputLabel value="Roles"/>
                <div v-for="role in roles" class="flex items-center">
                    <input :id="role"
                           v-model="form.role"
                           :value="role"
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 "
                           type="radio"
                           v-if="usePage().props.role === 'Super Admin' || role !== 'Super Admin'"
                    >
                    <label :for="role" class="ml-2 text-sm font-medium" v-if="usePage().props.role === 'Super Admin' || role !== 'Super Admin'">{{ role }}</label>
                </div>

                <InputError :message="form.errors.role" class="mt-2"/>
            </div>

            <div class="pt-5 space-y-2" v-if="usePage().props.role === 'Super Admin'">
                <InputLabel value="Permissions"/>
                <div v-for="permission in permissions">
                    <input id="brand.id"
                           v-model="form.permissions"
                           :value="permission"
                           class="cursor-pointer rounded text-blue-600 border-gray-300"
                           type="checkbox"
                    />
                    <label class="ml-2.5" for="brand.id">{{ permission }}</label>
                </div>

                <InputError :message="form.errors.permissions" class="mt-2"/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition class="transition ease-in-out" enter-from-class="opacity-0" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
            <InputError :message="form.errors.app" class="mt-2"/>
        </form>
    </section>
</template>
