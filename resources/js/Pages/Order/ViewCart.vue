<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import {ref} from "vue";
import ProductCart from "@/Pages/Order/Partials/ProductCart.vue";
import InputError from "@/Components/InputError.vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

const props = defineProps({
    cart: {
        type: Array,
        required: true
    }
});

const form = useForm({
    paymentMethod: 'PlaceToPay',
})

const subtotal = ref(props.cart.reduce((acc, item) => acc + (item.price * item.quantity), 0));
const processing = ref(false);

const deleteProduct = (id) => {
    location.reload();
}

const updateProduct = (id) => {
    subtotal.value = props.cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
}

const buyCart = () => {
    processing.value = true;
    form.post(route('cart.buy'), {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>


<template>
    <Head><title>Cart</title></Head>
    <UserLayout>
        <div class="flex flex-col lg:flex-row m-auto xl:px-10 lg:space-x-7" v-if="!processing">
            <div class="w-full bg-white mt-5 pb-5 rounded-2xl px-8 pt-5">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-10">Shopping Carts</h1>

                <ProductCart v-for="product in cart" :product="product" @delete-product="deleteProduct"
                             @update-product="updateProduct"/>

                <h2 class="text-xl pt-7 pr-3 text-end">Subtotal ({{ cart.length }}
                    {{ cart.length !== 1 ? 'items' : 'item' }}): <strong>$
                        {{ subtotal.toFixed(2) }}</strong></h2>
            </div>

            <div class="w-full max-w-md bg-white mt-5 pb-10 rounded-2xl px-10 h-fit mx-auto">
                <h2 class="text-xl pt-7 pr-3">Subtotal ({{ cart.length }} {{ cart.length !== 1 ? 'items' : 'item' }}):
                    <strong>$ {{ subtotal.toFixed(2) }}</strong></h2>
                <button class="bg-amber-300 w-full p-1 rounded-lg mt-5 active:bg-amber-400 font-semibold"
                        :disabled="processing"
                        @click="buyCart">
                    Proceed to checkout
                </button>
                <InputError :message="form.errors.paymentMethod" class="mt-4 ml-6"></InputError>
                <InputError :message="form.errors.app" class="mt-2"/>
            </div>
        </div>

        <div v-else class="py-12 flex justify-between">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <LoadingSpinner/>
            </div>
        </div>
    </UserLayout>
</template>
