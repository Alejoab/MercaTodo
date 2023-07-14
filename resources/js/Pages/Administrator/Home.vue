<script setup>
import {Head} from "@inertiajs/vue3";
import AdminHomeCard from "@/Pages/Administrator/partials/AdminHomeCard.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
</script>

<template>
    <Head><title>Administrator</title></Head>
    <UserLayout>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 px-6 lg:px-32 py-6">
            <AdminHomeCard :href="route('admin.users')">
                <template #icon>
                    <svg class="h-14 w-14 text-black" fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
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
                    <svg class="h-16 w-16 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5"/>
                    </svg>
                </template>
                <template #title>
                    <h5 class="font-semibold text-xl text-gray-800 leading-tight">Customers</h5>
                </template>
                <template #content>
                    <p class="mb-3 font-normal text-gray-700">Manage the different customers of the application.</p>
                </template>
            </AdminHomeCard>

            <AdminHomeCard :href="route('admin.products')">
                <template #icon>
                    <svg class="h-14 w-14 text-black" fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" x2="21" y1="6" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </template>
                <template #title>
                    <h5 class="font-semibold text-xl text-gray-800 leading-tight">Products</h5>
                </template>
                <template #content>
                    <p class="mb-3 font-normal text-gray-700">Manage the different products of the application.</p>
                </template>
            </AdminHomeCard>

            <AdminHomeCard :href="route('admin.reports')">
                <template #icon>
                    <svg class="h-16 w-16 text-black"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </template>
                <template #title>
                    <h5 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h5>
                </template>
                <template #content>
                    <p class="mb-3 font-normal text-gray-700">Generate reports of data from multiple application entities.</p>
                </template>
            </AdminHomeCard>
        </div>
    </UserLayout>
</template>
