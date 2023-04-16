<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    user: {
        type: Object,
    },
})

const passwordInput = ref(null);

const form = useForm({
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('admin.user.update.password', usePage().props.user.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};

const generatePassword = () => {
    let characterSet = '#abcdefghilkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789![]{}()%&*$^<>~@|';
    let password = '';

    for(let i=0; i < 32; i++) {
        password += characterSet.charAt(Math.floor(Math.random() * characterSet.length));
    }

    form.password = password;
    form.password_confirmation = password;
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
        </header>

        <div class="mt-6 space-y-6">
            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton @click="generatePassword">Generate Password</PrimaryButton>
                <PrimaryButton :disabled="form.processing" @click="updatePassword">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </div>
    </section>
</template>
