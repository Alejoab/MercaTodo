<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {onMounted, onUnmounted, ref} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    }
})

const errors = ref({});
const message = ref('pending');
let pollingInterval = null;

const emit = defineEmits(['close']);
const close = () => {
    emit('close');
};

const form = useForm({
    file: null,
})

const uploadFile = (e) => {
    form.file = e.target.files[0];
}

const submit = () => {
    message.value = 'pending';
    errors.value = {};
    axios.post(route('admin.products.import'), form, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(() => {
        pollingInterval = setInterval(() => checkImport(), 3000);
    }).catch((error) => {
        message.value = '';
        if (error.response.status === 422) {
            form.errors.file = error.response.data.errors.file[0];
        } else {
            form.errors.file = error.response.data.error;
        }
    });
}

const initialPolling = async () => {
    if (!await checkImport()) {
        pollingInterval = setInterval(() => checkImport(), 3000);
    }
}

const checkImport = async () => {
    const response = await axios.get(route('admin.products.import.check'));

    if (response.data.length === 0) {
        clearInterval(pollingInterval);
        message.value = '';
        return true;
    }

    if (response.data.status === 'Completed') {
        clearInterval(pollingInterval);
        if (response.data.data.length === 0) {
            message.value = 'All products have been imported successfully without any errors';
        } else {
            message.value = 'The following products have not been imported due to errors';
            errors.value = response.data.data;
        }
        return true;
    }

    if (response.data.status === 'Failed') {
        clearInterval(pollingInterval);
        message.value = 'An error has occurred in the data export. Please try again later';
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
            <p class="mt-1 text-sm text-gray-600">
                You can import product data. If you want to update a product it must have the same code that it has in
                the
                database. If you want to add a new product it must have a new code.
            </p>
            <h2 class="text-md font-semibold text-gray-900 mb-5 mt-7">
                New product data import
            </h2>
            <form class="flex justify-between bg-gray-100 rounded-md p-5" @submit.prevent="submit">
                <div class="my-auto">
                    <input
                        id="importFile"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        name="File Upload"
                        type="file"
                        @input="uploadFile"
                    >

                    <InputError :message="form.errors.file" class="mt-2"/>
                </div>
                <div v-if="message === 'pending'">
                    <div class="dots-loading items-center inline-block">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p class="ml-2 inline-block align-middle">Importing</p>
                </div>
                <div v-else>
                    <primary-button>Import</primary-button>
                </div>
            </form>

            <div v-if="message && message !== 'pending'" class="bg-gray-100 rounded-md p-5 max-h-96 mt-3 overflow-auto">
                <h2 class="text-md font-semibold text-gray-900">
                    {{ message }}
                </h2>
                <div class="space-y-7 mt-10">
                    <div v-for="(data, row) in errors">
                        <h2 class="text-md font-semibold text-gray-900 mb-2">
                            Product code: <span class="text-gray-600">{{ row }}</span>
                        </h2>
                        <ul v-for="error in data" class="px-8 mb-4">
                            <li v-for="i in error" class="text-md font-bold text-red-500 text-sm">{{ i }}</li>
                        </ul>
                    </div>
                </div>
            </div>
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
