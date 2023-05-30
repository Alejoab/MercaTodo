<script setup>
import {Head} from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import Order from "@/Pages/User/partials/Order.vue";

const props = defineProps({
    orders: {
        type: Object,
        required: true
    }
});
</script>


<template>
    <Head><title>Order History</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <h1 class="text-2xl font-semibold text-gray-900 mb-10">Order History</h1>
            <div class="space-y-7">
                <Order v-for="order in orders.order" :order="order"></Order>
            </div>
        </div>
    </UserLayout>
</template>
