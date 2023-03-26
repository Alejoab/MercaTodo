<script setup>
defineProps({
    href: {
        type: String,
        default: '#'
    }
})
</script>

<template>
    <a :href="href" class="text-xs md:text-md px-2 md:px-4 inline-flex items-center py-1 md:py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Back</a>
</template>
