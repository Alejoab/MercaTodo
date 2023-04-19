<script setup>
import GuestNavBar from "@/Components/GuestNavBar.vue";
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <GuestNavBar/>

        <div class="w-full sm:max-w-md mt-12 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg m-auto">
            <slot/>
        </div>
    </div>
</template>
