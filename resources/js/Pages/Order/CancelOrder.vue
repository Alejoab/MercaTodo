<script setup>

import {Head} from "@inertiajs/vue3";
</script>

<template>
    <Head><title>Cancel Order</title></Head>
    <div class="min-h-screen bg-gray-100">
        <div class="min-h-screen px-10 sm:px-20 flex justify-center flex-col text-gray-700">
            <div class="flex flex-row">
                <svg class="h-24 w-24 text-red-500 my-auto"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <circle cx="12" cy="12" r="10" />  <line x1="15" y1="9" x2="9" y2="15" />  <line x1="9" y1="9" x2="15" y2="15" /></svg>
                <h1 class="font-extrabold text-6xl md:text-7xl my-auto ml-5">
                    Order Cancelled
                </h1>
            </div>
            <div class="my-7 flex flex-col">
                <span class="font-semibold text-gray-500 text-xl md:text-2xl">
                    Your order has been cancelled and your cart has been emptied.
                </span>
                <div class="my-12">
                    <a class="bg-indigo-500 px-10 py-2 rounded-3xl cursor-pointer text-gray-800 text-2xl font-semibold" :href="route('home')">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
