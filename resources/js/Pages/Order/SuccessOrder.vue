<script setup>
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: String,
        required: true
    },
    order: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head><title>Payment</title></Head>
    <div class="min-h-screen bg-gray-100 flex flex-col-reverse lg:flex-row">
        <div class="lg:min-h-screen px-10 sm:px-20 flex justify-center flex-col">
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
                    The order was rejected.
                </span>
                <div class="my-12">
                    <a class="bg-indigo-500 px-10 py-2 rounded-3xl cursor-pointer text-gray-800 text-2xl font-semibold" :href="route('order.history')">
                        Back to MercaTodo
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gray-100 mx-auto my-20 lg:my-auto px-10 py-16 rounded-2xl shadow-md">
            <div class="mt-2">
                <div class="mb-7 text-xl font-bold text-gray-700">
                    Payment {{ order.status }}
                </div>
                <dl class="grid grid-cols-1 gap-x-20 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-md font-medium text-gray-500">
                            Order reference
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ order.code }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-md font-medium text-gray-500">
                            Total
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            $ {{ order.total }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-md font-medium text-gray-500">
                            Created at
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ new Date(order.created_at).toDateString() }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-md font-medium text-gray-500">
                            Payment Method
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ order.payment_method }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>
