<script setup>
import {Head} from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import ProductsTable from "@/Pages/Administrator/Products/partials/ProductsTable.vue";
</script>

<template>
    <Head><title>Products</title></Head>

    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <ProductsTable></ProductsTable>
        </div>
    </UserLayout>
</template>
