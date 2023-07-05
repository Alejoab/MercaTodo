<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    }
})

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
</script>

<template>
    <Modal :show="show" @close="close">
        <div class="p-6">
            <p class="mt-1 text-sm text-gray-600">
                You can import product data. If you want to update a product it must have the same id that it has in the
                database. If you want to add a new product it must have an empty id.
            </p>
            <h2 class="text-md font-semibold text-gray-900 mb-5 mt-7">
                New product data import
            </h2>
            <form class="flex justify-between bg-gray-100 rounded-md p-5">
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
                <primary-button>Import</primary-button>
            </form>

            <div class="bg-gray-100 rounded-md p-5 h-80 mt-3">
                {{ form.file }}
            </div>
        </div>
    </Modal>
</template>
