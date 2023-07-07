<script setup>
import {TailwindPagination} from "laravel-vue-pagination";
import {Link, router} from '@inertiajs/vue3';
import {ref} from "vue";

const props = defineProps({
    customers: {
        type: Object,
        required: true
    }
});

const search = ref('');

const getCustomers = async (page = 1, search = '') => {
    await router.visit(route('admin.customers', {page: page, search: search}), {
        preserveScroll: true,
        replace: true,
    });
}
</script>


<template>
    <div>
        <div class="mb-8 xl:ml-10">
            <div class="flex">
                <label class="sr-only" for="simple-search">Search</label>
                <div class="relative">
                    <input id="simple-search" v-model="search"
                           class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 pl-4 p-2.5"
                           placeholder="Search" required type="text">
                </div>
                <button class="p-1 ml-2" @click="getCustomers(1, search)">
                    <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" stroke="none"/>
                        <circle cx="10" cy="10" r="7"/>
                        <line x1="21" x2="15" y1="21" y2="15"/>
                    </svg>
                </button>
                <button class="p-1 ml-2" @click="getCustomers(1); search = ''">
                    <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" stroke="none"/>
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5"/>
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5"/>
                    </svg>
                </button>
            </div>
        </div>
        <div>
            <div class="overflow-auto">
                <table class="w-full text-xs md:text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50">
                    <th class="px-6 py-3" scope="col"> User ID</th>
                    <th class="px-6 py-3" scope="col"> Email</th>
                    <th class="px-6 py-3" scope="col"> Name</th>
                    <th class="px-6 py-3" scope="col"> Surname</th>
                    <th class="px-6 py-3" scope="col"> Document Type</th>
                    <th class="px-6 py-3" scope="col"> Document</th>
                    <th class="px-6 py-3" scope="col"> City</th>
                    <th class="px-6 py-3" scope="col"> Department</th>
                    <th class="px-6 py-3" scope="col"> Address</th>
                    <th class="px-6 py-3" scope="col"></th>
                    </thead>
                    <tbody>
                    <tr v-for="user in customers.data" class="bg-white border-b">

                        <th class="px-6 py-1.5 font-medium whitespace-nowrap" scope="row"> {{ user.id }}</th>
                        <td class="px-1 py-1.5"> {{ user.email }}</td>
                        <td class="px-4 py-1.5"> {{ user.name }}</td>
                        <td class="px-4 py-1.5"> {{ user.surname }}</td>
                        <td class="px-4 py-1.5"> {{ user.document_type }}</td>
                        <td class="px-4 py-1.5"> {{ user.document }}</td>
                        <td class="px-4 py-1.5"> {{ user.city }}</td>
                        <td class="px-4 py-1.5"> {{ user.department }}</td>
                        <td class="px-4 py-1.5"> {{ user.address }}</td>
                        <td class="px-4 py-1.5 text-center">
                            <div class="inline-flex space-x-1">
                                <Link :href="route('admin.customer.show', user.id)">
                                    <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path d="M0 0h24v24H0z" stroke="none"/>
                                        <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                                        <line x1="16" x2="19" y1="5" y2="8"/>
                                    </svg>
                                </Link>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-7 flex justify-center">
                <TailwindPagination :data="customers" :limit="1"
                                    @pagination-change-page="getCustomers()"></TailwindPagination>
            </div>
        </div>
    </div>
</template>
