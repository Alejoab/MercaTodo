<script setup>

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})
</script>


<template>
    <div class="w-full bg-white max-w-xs rounded-2xl">
        <a :href="route('products.show', product.id)">
            <img :alt="product.name" :src="'/product_images/' + product.image" class="p-8 rounded-t-lg"/>
        </a>
        <div class="px-5 pb-5">
            <a :href="route('products.show', product.id)">
                <h5 class="font-semibold tracking-tight text-gray-900">{{ product.name }}</h5>
            </a>

            <span class="text-gray-400">{{ product.brand_name }}</span> <br/>
            <span class="text-gray-400">{{ product.category_name }}</span>

            <div class="pt-5 flex items-center justify-between">
                <span class="text-xl font-bold text-gray-900">$ {{ product.price }}</span>
                <button
                    class="inline-flex items-center px-1.5 py-1.5 border border-transparent rounded-md font-semibold text-sm text-gray-800 tracking-widest bg-gray-200 hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</template>
