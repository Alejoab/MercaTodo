<script setup>
import {Head} from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import ProductsTable from "@/Pages/Administrator/Products/partials/ProductsTable.vue";

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
</script>

<template>
    <Head><title>Products</title></Head>

    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <ProductsTable :products="products" :brands="brands" :categories="categories"></ProductsTable>
        </div>
    </UserLayout>
</template>
