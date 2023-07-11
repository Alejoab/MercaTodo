<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {onMounted, onUnmounted, ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    query: {
        type: Object,
        required: true,
    }
})

const form = useForm({
    search: props.query.search,
    category: props.query.category,
    brand: props.query.brand,
})

const emit = defineEmits(['close']);
const close = () => {
    emit('close');
};

const exportFileName = ref('pending');
let pollingInterval = null;

const exportProducts = () => {
    exportFileName.value = 'pending';

    form.post(route('admin.products.export'), {
        onSuccess: () => {
            pollingInterval = setInterval(() => checkExport(), 3000)
        },
        onError: () => {
            exportFileName.value = '';
        }
    });
}

const initialPolling = async () => {
    if (!await checkExport()) {
        pollingInterval = setInterval(() => checkExport(), 3000);
    }
}
const checkExport = async () => {
    const response = await axios.get(route('admin.products.exports.check'));

    if (response.data.length === 0) {
        clearInterval(pollingInterval);
        exportFileName.value = '';
        return true;
    }

    if (response.data.status === 'Completed') {
        clearInterval(pollingInterval);
        exportFileName.value = route('admin.products.export.download');
        return true;
    }

    if (response.data.status === 'Failed') {
        clearInterval(pollingInterval);
        exportFileName.value = 'failed';
        return true;
    }

    return false;
}

onMounted(() => {
    initialPolling();
})

onUnmounted(() => {
    clearInterval(pollingInterval);
});
</script>

<template>
    <Modal :show="show" @close="close">
        <div class="p-6">
            <h2 class="text-md font-semibold text-gray-900 mt-2 mb-5">
                Export Product Data
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Exporting products will generate a XLSX file with all the filter products in your store.
            </p>
            <div class="flex justify-between mt-6">
                <primary-button :disabled="exportFileName === 'pending'" class="disabled:bg-gray-500"
                                @click="exportProducts">Export Products
                </primary-button>

                <div v-if="exportFileName === 'pending'" class="flex items-center">
                    <div class="dots-loading flex items-center">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p class="ml-2 inline-block align-middle">Generating</p>
                </div>

                <a v-else-if="exportFileName !== ''" :href="exportFileName" class="underline my-auto">Download
                    Export</a>
            </div>
            <InputError :message="form.errors.export" class="mt-3"></InputError>
        </div>
    </Modal>
</template>

<style>
.dots-loading {
    position: relative;
    width: 30px;
    height: 20px;
}

.dots-loading div {
    display: inline-block;
    width: 6px;
    height: 6px;
    margin: 0 2px;
    background: #3490dc;
    border-radius: 50%;
    animation: dots-loading 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
}

.dots-loading div:nth-child(1) {
    animation-delay: -0.24s;
}

.dots-loading div:nth-child(2) {
    animation-delay: -0.12s;
}

.dots-loading div:nth-child(3) {
    animation-delay: 0s;
}

@keyframes dots-loading {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.5);
    }
}
</style>
