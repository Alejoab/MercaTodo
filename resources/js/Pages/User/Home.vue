<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";
import ProductsList from "@/Pages/User/partials/ProductsList.vue";

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
    <Head><title>MercaTodo</title></Head>
    <UserLayout>
        <ProductsList :brands="brands" :categories="categories" :products="products"></ProductsList>
    </UserLayout>
</template>

