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
                    <svg class="h-16 w-16 text-black"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />  <circle cx="9" cy="7" r="4" />  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />  <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                </template>
                <template #title>
                    <h5 class="font-semibold text-xl text-gray-800 leading-tight">Users</h5>
                </template>
                <template #content>
                    <p class="mb-3 font-normal text-gray-700">Manage the different users of the application</p>
                </template>
            </AdminHomeCard>

            <AdminHomeCard :href="route('admin.customers')">
                <template #icon>
                    <svg class="h-20 w-20 text-black"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
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
