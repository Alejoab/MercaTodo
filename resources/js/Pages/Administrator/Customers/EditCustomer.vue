<script setup>
import ActionsAdmin from "@/Pages/Administrator/partials/ActionsAdmin.vue";
import BackButton from "@/Components/BackButton.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import UpdatePasswordAdmin from "@/Pages/Administrator/partials/UpdatePasswordAdmin.vue";
import UpdateCustomerInformation from "@/Pages/Administrator/Customers/partials/UpdateCustomerInformation.vue";
import {Head} from "@inertiajs/vue3";

defineProps({
    user: {
        type: Object
    },
    document_types: {
        type: Object
    },
    departments: {
        type: Object
    }
})
</script>

<template>
    <Head><title>Edit Customer</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="text-end pb-3 md:pb-0">
                    <BackButton :href="route('admin.customers')" ></BackButton>
                </div>
                <UpdateCustomerInformation :user="user" :departments="departments" :document_types="document_types"></UpdateCustomerInformation>
            </div>
        </div>
    </UserLayout>
</template>
