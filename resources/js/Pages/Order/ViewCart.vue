<script setup>
import {Head} from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import {ref} from "vue";
import ProductCart from "@/Pages/Order/Partials/ProductCart.vue";

const cart = ref(JSON.parse(localStorage.getItem('cart')) || []);
const subtotal = ref(cart.value.reduce((acc, item) => acc + (item.price * item.quantityToBuy), 0));

const removeProduct = (id) => {
    let index = cart.value.indexOf((item) => {
        if (item.id === id) {
            return item;
        }
    })

    cart.value.splice(index, 1);

    localStorage.setItem('cart', JSON.stringify(cart.value));
    location.reload();
}

const updateProduct = (id, quantityToBuy) => {
    if (quantityToBuy < 1) {
        removeProduct(id)
    }

    let item = cart.value.find((item) => {
        if (item.id === id) {
            return item;
        }
    })

    item.quantityToBuy = quantityToBuy;

    localStorage.setItem('cart', JSON.stringify(cart.value));
    location.reload();
}
</script>


<template>
    <Head><title>Cart</title></Head>
    <UserLayout>
        <div class="flex flex-col lg:flex-row m-auto xl:px-10 lg:space-x-7">
            <div class="w-full bg-white mt-5 pb-5 rounded-2xl px-8 pt-5">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-10">Shopping Cart</h1>

                <ProductCart v-for="product in cart" :product="product" @remove-product="removeProduct"
                             @update-product="updateProduct"/>

                <h2 class="text-xl pt-7 pr-3 text-end">Subtotal ({{ cart.length }}
                    {{ cart.length !== 1 ? 'items' : 'item' }}): <strong>$
                        {{ Math.round((subtotal + Number.EPSILON) * 100) / 100 }}</strong></h2>
            </div>

            <div class="w-full max-w-md bg-white mt-5 pb-10 rounded-2xl px-10 h-fit">
                <h2 class="text-xl pt-7 pr-3">Subtotal ({{ cart.length }} {{ cart.length !== 1 ? 'items' : 'item' }}):
                    <strong>$ {{ Math.round((subtotal + Number.EPSILON) * 100) / 100 }}</strong></h2>
                <button class="bg-amber-300 w-full p-1 rounded-lg mt-5 active:bg-amber-400 font-semibold">
                    Proceed to checkout
                </button>
            </div>
        </div>
    </UserLayout>
</template>
