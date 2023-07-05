<script setup>
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const emit = defineEmits(['updateProduct', 'deleteProduct'])

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const form = useForm({
    product_id: props.product.id,
    quantity: props.product.quantity
})

const isNumber = (evt) => {
    const keysAllowed = new Set(['1', '2', '3', '4', '5', '6', '7', '8', '9', '0']);
    const keyPressed = evt.key;

    if (!keysAllowed.has(keyPressed)) {
        evt.preventDefault()
    }
}

const updateProduct = async () => {
    form.post(route('cart.add'), {
        preserveScroll: true,
        onSuccess: () => {
            props.product.quantity = form.quantity;
            emit('updateProduct', props.product.id)
        },
        onError: () => {
        }
    })
}

const deleteProduct = async () => {
    let response = await axios.delete(route('cart.delete', {product_id: props.product.id}));

    if (response.status === 200) {
        emit('deleteProduct', props.product.id)
    } else {
    }
}
</script>

<template>
    <div class="w-full flex border-b-2 border-t-2 py-3 relative">
        <button class="absolute top-2 right-0" @click="deleteProduct">
            <svg class="h-4 w-4 text-gray-500" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                 stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                <path d="M0 0h24v24H0z" stroke="none"/>
                <line x1="18" x2="6" y1="6" y2="18"/>
                <line x1="6" x2="18" y1="6" y2="18"/>
            </svg>
        </button>

        <div>
            <img :alt="product.name" :src="product.image ? '/storage/product_images/' + product.image : '/storage/default/no_product_image.png'" class="w-80 rounded-lg">
        </div>

        <div class="flex justify-between w-full">
            <div class="my-auto pl-5">
                <div>
                    <h1 class="font-semibold text-xl text-black leading-tight pl-3">{{ product.name }}</h1>
                    <span class="text-gray-400 pl-3">{{ product.brand }}</span> <br/>
                </div>

                <div>
                    <input
                        v-model.number="form.quantity"
                        class="border border-gray-300 rounded-md px-1 py-1 focus:border-indigo-700 mt-5"
                        type="text"
                        @keypress="isNumber"
                    >
                    <button
                        v-show="form.quantity !== product.quantity && form.quantity !== ''"
                        class="text-gray-500 underline ml-3"
                        @click="updateProduct"
                    >
                        Update
                    </button>
                </div>
                <InputError class="mt-2" :message="form.errors.quantity" />
            </div>

            <div>
                <h2 class="text-xl font-bold pt-7 pr-3">$ {{ product.price }}</h2>
            </div>
        </div>
    </div>
</template>
