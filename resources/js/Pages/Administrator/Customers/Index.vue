<script setup>
import {Head} from "@inertiajs/vue3";
import BackButton from "@/Components/BackButton.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import CustomersTable from "@/Pages/Administrator/Customers/partials/CustomersTable.vue";
</script>

<template>
    <Head><title>Customers</title></Head>

    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <div class="text-end pb-3 md:pb-0">
                <BackButton :href="route('admin')" ></BackButton>
            </div>
            <CustomersTable></CustomersTable>
        </div>
    </UserLayout>
</template>
