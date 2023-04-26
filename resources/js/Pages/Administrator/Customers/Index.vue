<script setup>
import {Head} from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import CustomersTable from "@/Pages/Administrator/Customers/partials/CustomersTable.vue";
</script>

<template>
    <Head><title>Customers</title></Head>

    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <CustomersTable></CustomersTable>
        </div>
    </UserLayout>
</template>
