<script setup>
import {useCartCounter} from "@/Store/CounterStore";

const cartCounter = useCartCounter();
cartCounter.syncCartCount();

</script>


<template>
    <a :href="route('cart')" class="relative">
        <span v-show="cartCounter.count > 0"
              class="absolute w-5 h-5 rounded-full flex justify-center items-center bg-amber-300 -top-0.5 -right-2 text-gray-600 font-bold">{{ cartCounter.count }}</span>
        <svg class="h-9 w-9 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2"/>
        </svg>
    </a>
</template>
