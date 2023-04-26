<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";
import UpdateProductInformation from "@/Pages/Administrator/Products/partials/UpdateProductInformation.vue";
import ProductActionsAdmin from "@/Pages/Administrator/Products/partials/ProductActionsAdmin.vue";

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})
</script>


<template>
    <Head><title>Edit Product</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdateProductInformation :product="props.product"></UpdateProductInformation>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <ProductActionsAdmin :product="props.product"></ProductActionsAdmin>
            </div>
        </div>
    </UserLayout>
</template>
