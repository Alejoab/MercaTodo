<script setup>
import { TailwindPagination } from "laravel-vue-pagination";
import {Head, Link} from '@inertiajs/vue3';
import {ref} from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

const users = ref({});
const search = ref('');
let pageT = 1;

const getResults = async (page = 1, search = '') => {
    pageT = page;
    const response = await fetch(route('admin.list-customers', {page: page, search: search}));
    users.value = await response.json();
}

getResults();
</script>


<template>
    <div>
        <div class="mb-8 xl:ml-10">
            <div class="flex">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative">
                    <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 pl-4 p-2.5" placeholder="Search" required v-model="search">
                </div>
                <button  class="p-1 ml-2" @click="getResults(1, search)">
                    <svg class="h-8 w-8 text-black"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="10" cy="10" r="7" />  <line x1="21" y1="21" x2="15" y2="15" /></svg>
                </button>
                <button  class="p-1 ml-2" @click="getResults(1); search = ''">
                    <svg class="h-8 w-8 text-black"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5" />  <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5" /></svg>
                </button>
            </div>
        </div>
        <div class="overflow-auto">
            <table class="w-full text-xs md:text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50">
                <th scope="col" class="px-6 py-3"> User ID </th>
                <th scope="col" class="px-6 py-3"> Email </th>
                <th scope="col" class="px-6 py-3"> Name </th>
                <th scope="col" class="px-6 py-3"> Surname </th>
                <th scope="col" class="px-6 py-3"> Document Type </th>
                <th scope="col" class="px-6 py-3"> Document </th>
                <th scope="col" class="px-6 py-3"> City </th>
                <th scope="col" class="px-6 py-3"> Department </th>
                <th scope="col" class="px-6 py-3"> Address </th>
                <th scope="col" class="px-6 py-3">  </th>
                </thead>
                <tbody>
                <tr v-for="user in users.data" class="bg-white border-b">

                    <th scope="row" class="px-6 py-1.5 font-medium whitespace-nowrap"> {{ user.id }} </th>
                    <td class="px-1 py-1.5"> {{user.email}} </td>
                    <td class="px-4 py-1.5"> {{user.name}} </td>
                    <td class="px-4 py-1.5"> {{user.surname}} </td>
                    <td class="px-4 py-1.5"> {{user.document_type}} </td>
                    <td class="px-4 py-1.5"> {{user.document}} </td>
                    <td class="px-4 py-1.5"> {{user.city}} </td>
                    <td class="px-4 py-1.5"> {{user.department}} </td>
                    <td class="px-4 py-1.5"> {{user.address}} </td>
                    <td class="px-4 py-1.5 text-center">
                        <div class="inline-flex space-x-1">
                            <Link :href="route('admin.customer.show', user.id)">
                                <svg class="h-6 w-6 text-black"  viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />  <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />  <line x1="16" y1="5" x2="19" y2="8" /></svg>
                            </Link>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="mt-7 flex justify-center">
                <TailwindPagination :data="users" @pagination-change-page="getResults" :limit="1"></TailwindPagination>
            </div>
        </div>
    </div>
</template>
