<script setup>

import DangerButton from "@/Components/DangerButton.vue";
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

defineProps({
    product: {
        type: Object
    }
})

const product = usePage().props.product;
const confirmProductDelete = ref(false);
const destroyProductId = ref('');
const restoreProductId = ref('');

const destroyProduct = async (id) => {
    destroyProductId.value = '';
    // await axios.delete(route('admin.product.destroy', id));
    // window.location.href = route('admin.products');
}

const restoreProduct = async (id) => {
    restoreProductId.value = '';
    // await axios.put(route('admin.product.restore', id));
    // window.location.href = route('admin.products');
}

const confirmProductDeletion = () => {
    confirmProductDelete.value = true;
};

const deleteProduct = () => {
    // await axios.put(route('admin.product.restore', id));
    // window.location.href = route('admin.products');
};

const closeModal = () => {
    confirmProductDelete.value = false;
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Actions</h2>
        </header>
        <div class="mt-4">
            <div v-if="product.deleted_at">
                <SuccessButton class="mr-5" @click="restoreProductId = product.id">Restore Product</SuccessButton>
                <DangerButton @click="confirmProductDeletion"> Force Delete Product</DangerButton>
            </div>
            <DangerButton v-else @click="destroyProductId = product.id">Delete Product</DangerButton>
        </div>

        <Modal :show="confirmProductDelete" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this Product?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once the product is deleted, all of its resources and data will be permanently deleted. Please
                    confirm you would like to permanently delete this product.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        @click="deleteProduct"
                    >
                        Delete Product
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <Modal :show="!! destroyProductId" @close="destroyProductId = ''">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to disable this product?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once you disable this product the customers will not be able to access it.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="destroyProductId = ''">Cancel</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        @click="destroyProduct(destroyProductId)"
                    >
                        Delete Product
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <Modal :show="!! restoreProductId" @close="restoreProductId = ''">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to restore this product?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once you restore this product the customers will have access to it again.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="restoreProductId = ''">Cancel</SecondaryButton>

                    <SuccessButton
                        class="ml-3"
                        @click="restoreProduct(restoreProductId)"
                    >
                        Restore Product
                    </SuccessButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

