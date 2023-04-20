<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";
import ProductsList from "@/Pages/User/partials/ProductsList.vue";
</script>

<template>
    <Head><title>MercaTodo</title></Head>
    <UserLayout>
        <ProductsList></ProductsList>
    </UserLayout>
</template>

