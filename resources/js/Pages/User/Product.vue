<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const isNumber = (evt) => {
    const keysAllowed = new Set(['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.']);
    const keyPressed = evt.key;

    if (!keysAllowed.has(keyPressed)) {
        evt.preventDefault()
    }
}
</script>

<template>
    <Head><title>{{product.name}}</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center md:items-start">
                    <div class="w-[80%]">
                        <img :src="'/product_images/' + product.image" alt="">
                    </div>

                    <div class="w-full flex flex-col items-center mt-10 md:mt-0">
                        <div>
                            <div>
                                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold">{{ product.name }}</h1>

                                <h2 class="text-gray-600">{{ product.brand.name }}</h2>
                                <h2 class="text-xl font-bold pt-6">$ {{ product.price }}</h2>
                            </div>
                            <div class="flex flex-col space-y-5 w-full mt-12 px-7">
                                <div class="flex items-center">
                                    <InputLabel class="text-xl mr-4" value="Cantidad"></InputLabel>

                                    <input :placeholder="product.stock + ' avaliable'"
                                           class="border border-gray-300 rounded-md px-2 py-2 w-full focus:border-indigo-700"
                                           type="text"
                                           @keypress="isNumber">
                                </div>
                                <button
                                    class="bg-indigo-700 font-bold text-white tex px-4 py-2 rounded-md lg:text-xl hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Buy Now
                                </button>
                                <button
                                    class="bg-blue-100 font-bold text-blue-600 px-4 py-2 rounded-md lg:text-xl hover:bg-indigo-200 focus:bg-indigo-200 active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-16">
                    {{ product.description }}
                </div>
            </div>
        </div>
    </UserLayout>
</template>