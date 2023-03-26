<script setup>
import AuthenticatedLayout from '@/Layouts/UserLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateAddressInformation from "@/Pages/Profile/Partials/UpdateAddressInformation.vue";
import BackButton from "@/Components/BackButton.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    departments: {
        type: Object
    },
    department_id: {
        type: Number
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="relative">
                        <div class="absolute top-0 right-0">
                            <BackButton :href="route('home')"></BackButton>
                        </div>
                    </div>
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl mt-5 sm:mt-0"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateAddressInformation
                        :departments="departments"
                        :department_id="department_id"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
