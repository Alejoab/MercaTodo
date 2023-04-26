<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";
import ProductInformation from "@/Pages/Administrator/Products/partials/ProductInformation.vue";
</script>


<template>
    <Head><title>Create Product</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <ProductInformation></ProductInformation>
            </div>
        </div>
    </UserLayout>
</template>
