<script setup>
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const search = ref(usePage().props.ziggy.query['search'] ? usePage().props.ziggy.query['search'] : '');

const replaceRoute = () => {
    window.location.href = route('home', {search: search.value});
}
</script>


<template>
    <div class="relative flex items-center w-full">
        <input id="default-search" v-model="search"
               class="block w-full p-3 pl-5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500"
               placeholder="Search Products ..." type="search">
        <button class="-ml-10" type="submit" @click="replaceRoute">
            <svg class="h-8 w-8 text-gray-600" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                 stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                <path d="M0 0h24v24H0z" stroke="none"/>
                <circle cx="10" cy="10" r="7"/>
                <line x1="21" x2="15" y1="21" y2="15"/>
            </svg>
        </button>
    </div>
</template>
