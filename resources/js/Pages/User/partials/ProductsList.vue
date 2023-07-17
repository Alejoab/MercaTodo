<script setup>
import {ref} from "vue";
import ProductCard from "@/Pages/User/partials/ProductCard.vue";
import Dropdown from "@/Components/Dropdown.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {TailwindPagination} from "laravel-vue-pagination";
import {router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    products: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    brands: {
        type: Array,
        required: true
    }
});

const sorts = {
    0: 'Price: Low to High',
    1: 'Price: High to Low',
    2: 'Newest',
    3: 'Oldest',
}

const query = ref({
    sortBy: usePage().props.ziggy.query['sortBy'] ? usePage().props.ziggy.query['sortBy'] : 2,
    page: usePage().props.ziggy.query['page'] ? usePage().props.ziggy.query['page'] : 1,
    search: usePage().props.ziggy.query['search'] ? usePage().props.ziggy.query['search'] : '',
    category: usePage().props.ziggy.query['category'] ? usePage().props.ziggy.query['category'] : '',
    brand: usePage().props.ziggy.query['brand'] ? usePage().props.ziggy.query['brand'] : [],
});

const brands = ref(props.brands);
const showFilter = ref(false);

const getProducts = async (page = 1) => {
    query.value.page = page;
    await router.visit(route('home', query.value), {
        replace: true,
    });
}

const clearQuery = async () => {
    await router.visit(route('home'), {
        preserveScroll: true,
        replace: true,
    });
}

const getBrands = async () => {
    const response = await fetch(route('brands', query.value.category));
    brands.value = await response.json();
}
</script>


<template>
    <div>
        <div v-show="showFilter" class="fixed inset-0 z-40 bg-gray-600 opacity-60"
             @click="showFilter = false"></div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >

            <div v-show="showFilter"
                 class="max-w-xs bg-white fixed inset-0 z-50 flex flex-col pt-16 px-6 justify-between">
                <div class="space-y-6">

                    <div class="mt-4">
                        <InputLabel for="category" value="Categories"/>

                        <select
                            id="category"
                            v-model="query.category"
                            class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            required
                            @change="getBrands(); query.brand = [];"
                        >
                            <option selected value="">All</option>
                            <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>

                    <div class="space-y-2 pl-2 h-30 overflow-y-auto">
                        <InputLabel value="Brands"/>
                        <div v-for="brand in brands">
                            <input id="brand.id" v-model="query.brand" :value="brand.id"
                                   class="cursor-pointer rounded text-indigo-600 focus:ring-indigo-700 focus:ring-2"
                                   type="checkbox"/>
                            <label class="ml-2.5" for="brand.id">{{ brand.name }}</label>
                        </div>
                    </div>
                </div>

                <div class="mb-10 flex justify-between px-3">
                    <SecondaryButton @click="clearQuery()">Clear</SecondaryButton>
                    <PrimaryButton @click="getProducts()">Search</PrimaryButton>
                </div>
            </div>
        </transition>
    </div>


    <div class="m-auto max-w-7xl w-full">
        <div class="flex flex-col space-y-5 md:space-y-0 md:flex-row md:justify-between px-5 md:px-10 lg:px-20">
            <div class="flex items-center">
                <button class="inline-flex transition ease-in-out duration-150 items-center"
                        @click="showFilter = true">
                    <span class="text-gray-500 font-bold text-xl">Filter</span>
                    <svg class="ml-1 h-6 w-6 text-gray-500" fill="none" height="24" stroke="currentColor"
                         stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" stroke="none"/>
                        <path d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5"/>
                    </svg>
                </button>
            </div>

            <Dropdown align="right" width="48">
                <template #trigger>
                    <button class="inline-flex transition ease-in-out duration-150 items-center">
                        <span class="text-gray-500 font-bold text-xl">Sort by: {{ sorts[query.sortBy] }}</span>
                        <svg class="ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        </svg>
                    </button>
                </template>

                <template #content>
                    <div class="flex flex-col">
                        <button v-for="(value, name) in sorts" class="text-start p-2 pl-3"
                                @click="query.sortBy = name; getProducts()">{{ value }}
                        </button>
                    </div>
                </template>
            </Dropdown>
        </div>


        <div class="bg-white mt-5 pb-5 rounded-2xl">
            <div class="justify-items-center grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-y-5">
                <product-card v-for="product in products.data" :product="product" class="text-sm"></product-card>
            </div>

            <div class="mt-7 flex justify-center">
                <TailwindPagination
                    :data="products"
                    :limit="1"
                    @pagination-change-page="getProducts"
                    :keepLength="true"
                >
                </TailwindPagination>
            </div>
        </div>
    </div>
</template>
