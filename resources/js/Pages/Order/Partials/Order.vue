<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    order: {
        type: Object,
        required: true
    }
})

const open = ref(false);

const form = useForm({
    orderId: props.order.id,
});

const tryAgain = () => {
    form.post(route('payment.retry'), {
        preserveScroll: true,
    });
}
</script>


<template>
    <div>
        <div
            :class="{ 'bg-gray-100': open, 'bg-white': !open}"
            class="w-full shadow-md p-7 flex justify-between relative rounded-2xl"
            @click="open = !open"
        >
            <div class="absolute right-3 top-3">
                <svg :class="{'rotate-180': open}" class="transition h-6 w-6 text-gray-500" fill="none" height="24"
                     stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                     width="24">
                    <path d="M0 0h24v24H0z" stroke="none"/>
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>

            <div class="flex space-x-28">
                <div>
                    <h1 class="font-bold">ORDER {{ order.status.toUpperCase() }}</h1>
                    {{ new Date(order.created_at).toDateString() }}
                </div>

                <div>
                    <h1 class="font-bold">TOTAL</h1>
                    ${{ order.total }}
                </div>
            </div>

            <div class="pt-2 pr-4">
                {{ order.code }}
            </div>
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="z-50 rounded-md shadow-lg p-7 bg-gray-200"
            >
                <div>
                    <div v-for="detail in order.order_detail" class="flex justify-between space-y-12">
                        <div class="my-auto">
                            <h1 class="font-semibold text-xl text-black leading-tight">{{ detail.product_name }}</h1>
                            Quantity: {{ detail.quantity }}
                        </div>
                        <div class="text-start">
                            <strong>$ {{ detail.amount }}</strong>
                        </div>
                    </div>
                </div>

                <div v-if="order.active" class="text-end mt-10 mb-5">
                    <button
                        class="bg-yellow-400 rounded-lg px-5 py-1 font-semibold hover:bg-amber-400 active:bg-yellow-500 focus:outline-none transition ease-in-out duration-150 border border-transparent"
                        @click="tryAgain"
                    >
                        RETRY PAYMENT
                    </button>
                </div>
                <InputError :message="form.errors.orderId"></InputError>
                <InputError :message="form.errors.payment"></InputError>
                <InputError :message="form.errors.app"></InputError>
            </div>
        </transition>
    </div>
</template>

