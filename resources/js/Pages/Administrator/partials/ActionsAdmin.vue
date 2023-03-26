<script setup>

import DangerButton from "@/Components/DangerButton.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {nextTick, ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";

defineProps({
    user: {
        type: Object
    }
})

const user = usePage().props.user;
const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const destroyUser = async (id) => {
    await axios.delete(route('admin.user.destroy', id));
    window.location.href = route('admin.users');
}

const restoreUser = async (id) => {
    await axios.put(route('admin.user.restore', id));
    window.location.href = route('admin.users');
}

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('admin.user.force-delete', user.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Actions</h2>
    </header>
    <div class="mt-4">
        <div v-if="user.deleted_at">
            <DangerButton @click="restoreUser(user.id)" class="bg-green-500 mr-5 hover:bg-green-900 active:bg-green-700 focus:ring-green-700">Restore Account</DangerButton>
            <DangerButton @click="confirmUserDeletion"> Force Delete Account </DangerButton>
        </div>
            <DangerButton v-else @click="destroyUser(user.id)">Delete Account</DangerButton>
    </div>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete the account?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once the account is deleted, all of its resources and data will be permanently deleted. Please
                enter your password to confirm you would like to permanently delete the account.
            </p>

            <div class="mt-6">
                <InputLabel for="password" value="Password" class="sr-only" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    @keyup.enter="deleteUser"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteUser"
                >
                    Delete Account
                </DangerButton>
            </div>
        </div>
    </Modal>
</section>
</template>

