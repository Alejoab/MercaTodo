<script setup>
import { Head } from "@inertiajs/vue3";
import UsersTable from "@/Pages/Administrator/Users/partials/UsersTable.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import BackButton from "@/Components/BackButton.vue";

</script>

<template>
    <Head><title>Users</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <div class="text-end pb-3 md:pb-0">
                <BackButton :href="route('admin')" ></BackButton>
            </div>
            <UsersTable></UsersTable>
        </div>
    </UserLayout>
</template>
