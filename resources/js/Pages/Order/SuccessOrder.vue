<script setup>
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: String,
        required: true
    }
})
</script>

<template>
    <Head><title>Payment</title></Head>
    <div class="min-h-screen bg-gray-100">
        <div class="min-h-screen px-10 sm:px-20 flex justify-center flex-col bg-gray-100">
            <div class="flex flex-row" v-if="status === 'Pending'">
                <svg class="h-24 w-24 text-yellow-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="12" cy="12" r="9" />  <line x1="12" y1="8" x2="12" y2="12" />  <line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                <h1 class="font-extrabold text-6xl md:text-7xl my-auto ml-5 text-gray-700">
                    Order Pending
                </h1>
            </div>
            <div class="flex flex-row" v-else-if="status === 'Accepted'">
                <svg class="h-24 w-24 text-green-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="12" cy="12" r="9" />  <path d="M9 12l2 2l4 -4" /></svg>
                <h1 class="font-extrabold text-6xl md:text-7xl my-auto ml-5 text-gray-700">
                    Order Accepted
                </h1>
            </div>
            <div class="flex flex-row" v-else>
                <svg class="h-24 w-24 text-red-500 my-auto"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <circle cx="12" cy="12" r="10" />  <line x1="15" y1="9" x2="9" y2="15" />  <line x1="9" y1="9" x2="15" y2="15" /></svg>
                <h1 class="font-extrabold text-6xl md:text-7xl my-auto ml-5 text-gray-700">
                    Order Rejected
                </h1>
            </div>

            <div class="my-7 flex flex-col">
                <span class="font-semibold text-gray-500 text-xl md:text-2xl" v-if="status === 'Pending'">
                    The payment is still pending. Please try again later.
                </span>
                <span class="font-semibold text-gray-500 text-xl md:text-2xl" v-else-if="status === 'Accepted'">
                    The payment was successful
                </span>
                <span class="font-semibold text-gray-500 text-xl md:text-2xl" v-else>
                    The order was rejected. If you want to try again, please add the items to your cart again.
                </span>
                <div class="my-12">
                    <a class="bg-indigo-500 px-10 py-2 rounded-3xl cursor-pointer text-gray-800 text-2xl font-semibold" :href="route('order.history')">
                        Back to MercaTodo
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
