<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <a :href="route('admin.users')" class="transition ease-in-out delay-75 hover:scale-105 p-1 pl-6 flex flex-col items-center bg-white border border-gray-100 border-2 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100">
        <slot name="icon"/>
        <div class="flex flex-col justify-between p-4 leading-normal">
            <slot name="title"/>
            <slot name="content"/>
        </div>
    </a>
</template>
