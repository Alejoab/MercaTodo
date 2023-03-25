<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import UsersTable from "@/Pages/Administrator/partials/UsersTable.vue";

</script>

<template>
    <Head><title>Users</title></Head>
    <AdminLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <UsersTable></UsersTable>
        </div>
    </AdminLayout>
</template>
