<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AdminHomeCard from "@/Pages/Administrator/partials/AdminHomeCard.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
</script>

<template>
    <Head><title>Administrator</title></Head>
    <UserLayout>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 px-6 lg:px-32 py-6">
            <AdminHomeCard :href="route('admin.users')">
                <template #icon>
                    <svg class="h-20 w-20 text-black"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />  <circle cx="9" cy="7" r="4" />  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />  <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                </template>
                <template #title>
                    <h5 class="font-semibold text-xl text-gray-800 leading-tight">Users</h5>
                </template>
                <template #content>
                    <p class="mb-3 font-normal text-gray-700">Manage the different users of the application. You can edit them, change their roles and delete or restore their accounts</p>
                </template>
            </AdminHomeCard>

            <AdminHomeCard :href="route('admin.customers')">
                <template #icon>
                    <svg class="h-20 w-20 text-black"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />  <circle cx="9" cy="7" r="4" />  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />  <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                </template>
                <template #title>
                    <h5 class="font-semibold text-xl text-gray-800 leading-tight">Customers</h5>
                </template>
                <template #content>
                    <p class="mb-3 font-normal text-gray-700">Manage the different customers of the application.</p>
                </template>
            </AdminHomeCard>
        </div>
    </UserLayout>
</template>
