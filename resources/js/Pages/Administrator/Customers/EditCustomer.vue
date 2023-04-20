<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
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
                <UpdateCustomerInformation :departments="departments" :document_types="document_types"
                                           :user="user"></UpdateCustomerInformation>
            </div>
        </div>
    </UserLayout>
</template>
