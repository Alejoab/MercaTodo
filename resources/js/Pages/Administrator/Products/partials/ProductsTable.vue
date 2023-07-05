<script setup>
import {TailwindPagination} from "laravel-vue-pagination";
import {Link, usePage} from '@inertiajs/vue3';
import {onMounted, onUnmounted, ref} from "vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import ImportModal from "@/Pages/Administrator/Products/partials/ImportModal.vue";

const products = ref({});
const categories = ref([]);
const brands = ref([]);
const destroyProductId = ref('');
const restoreProductId = ref('');
const exportModal = ref(false);
const exportFileName = ref('');
const exportError = ref('');
const showImportModal = ref(false);

let pollingInterval = null;

const query = ref({
    page: usePage().props.ziggy.query['page'] ? usePage().props.ziggy.query['page'] : 1,
    search: usePage().props.ziggy.query['search'] ? usePage().props.ziggy.query['search'] : '',
    category: usePage().props.ziggy.query['category'] ? usePage().props.ziggy.query['category'] : '',
    brand: usePage().props.ziggy.query['brand'] ? usePage().props.ziggy.query['brand'] : '',
})

const clearQuery = () => {
    query.value = {
        page: 1,
        search: '',
        category: '',
        brand: '',
    }

    replaceRoute();
}

const getCategories = async () => {
    const response = await fetch(route('categories'));
    categories.value = await response.json();
}

const getBrands = async () => {
    const response = await fetch(route('brands', query.value.category));
    brands.value = await response.json();
}

const getProducts = async () => {
    const response = await fetch(route('admin.list-products', query.value));
    products.value = await response.json();
}

const replaceRoute = () => {
    window.location.href = route('admin.products', query.value);
}

const destroyProduct = async (id) => {
    destroyProductId.value = '';
    await axios.delete(route('admin.products.destroy', id));
    await getProducts();
}

const restoreProduct = async (id) => {
    restoreProductId.value = '';
    await axios.put(route('admin.products.restore', id));
    await getProducts()
}

const exportProducts = () => {
    exportError.value = '';
    axios.get(route('admin.products.export', query.value))
        .then((response) => startPolling())
        .catch((error) => exportError.value = error.response.data.error);
}

const startPolling = async () => {
    if (!await checkExport()) {
        exportFileName.value = 'pending';
        pollingInterval = setInterval(() => checkExport(), 3000);
    }
}
const checkExport = async () => {
    const response = await axios.get(route('admin.products.exports.check-export'));


    if (response.data.length === 0) {
        clearInterval(pollingInterval);
        return true;
    }

    if (response.data.status === 'Completed') {
        clearInterval(pollingInterval);
        exportFileName.value = route('admin.products.export.download');
        exportError.value = '';
        return true;
    }

    if (response.data.status === 'Failed') {
        clearInterval(pollingInterval);
        exportFileName.value = 'failed';
        exportError.value = 'An error has occurred in the data export. Please try again later';
        return true;
    }

    return false;
}


onMounted(() => {
    getProducts();
    getCategories();
    getBrands();
    startPolling();
})

onUnmounted(() => {
    clearInterval(pollingInterval);
});

</script>


<template>
    <div>
        <div class="flex flex-col lg:flex-row xl:pl-10 justify-between mt-5">
            <div class="flex flex-col md:flex-row my-auto md:space-x-4 space-y-3 md:space-y-0 mx-auto">
                <div>
                    <input
                        id="simple-search" v-model="query.search"
                        class="bg-gray-50 border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5"
                        placeholder="Search"
                        required
                        type="text"
                    >
                </div>

                <div>
                    <select
                        id="category_name"
                        v-model.number="query.category"
                        autocomplete="category_name"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-60"
                        required
                        @change="getBrands(); query.brand = '';"
                    >
                        <option disabled selected value="">Select a Category</option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>

                <div>
                    <select
                        id="brand_name"
                        v-model.number="query.brand"
                        autocomplete="brand_name"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-60"
                        required
                    >
                        <option disabled selected value="">Select a Brand</option>
                        <option v-for="brand in brands" :value="brand.id">{{ brand.name }}</option>
                    </select>
                </div>
            </div>

            <div class="w-full flex justify-between mt-5 lg:mt-0 px-10 lg:px-0">
                <div class="my-auto">
                    <button class="p-1 ml-2" @click="query.page = 1; replaceRoute()">
                        <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor"
                             stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0z" stroke="none"/>
                            <circle cx="10" cy="10" r="7"/>
                            <line x1="21" x2="15" y1="21" y2="15"/>
                        </svg>
                    </button>

                    <button class="p-1 ml-2" @click="clearQuery">
                        <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor"
                             stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0z" stroke="none"/>
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5"/>
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5"/>
                        </svg>
                    </button>
                </div>

                <div class="my-auto">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="inline-flex transition ease-in-out duration-150 items-center bg-gray-800 px-3 py-1 rounded-xl">
                                <span class="text-white font-semibold text-md">Actions</span>
                                <svg class="ml-1 h-5 w-5 text-white" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"/>
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="flex flex-col space-y-2">
                                <a :href="route('admin.products.create')" class="text-start p-2 pl-3">Add Product</a>
                                <button class="text-start p-2 pl-3" @click="exportModal = true">Export Products</button>
                                <button class="text-start p-2 pl-3" @click="showImportModal = true">Import Products
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="overflow-auto">
                <table class="w-full text-xs md:text-sm text-left">
                    <tr class="text-xs uppercase bg-gray-50">
                        <th class="px-6 py-3" scope="col"> Code</th>
                        <th class="px-6 py-3" scope="col"> Name</th>
                        <th class="px-6 py-3" scope="col"> Price</th>
                        <th class="px-6 py-3" scope="col"> Stock</th>
                        <th class="px-6 py-3" scope="col"> Category</th>
                        <th class="px-6 py-3" scope="col"> Brand</th>
                        <th class="text-center" scope="col"> Status</th>
                        <th class="px-6 py-3" scope="col"></th>
                    </tr>
                    <tbody>
                    <tr v-for="product in products.data" class="bg-white border-b">
                        <th class="px-6 py-1.5 font-medium whitespace-nowrap" scope="row"> {{ product.code }}</th>
                        <td class="px-1 py-1.5"> {{ product.name }}</td>
                        <td class="px-4 py-1.5"> $ {{ product.price }}</td>
                        <td class="px-4 py-1.5"> {{ product.stock }}</td>
                        <td class="px-4 py-1.5"> {{ product.category_name }}</td>
                        <td class="px-4 py-1.5"> {{ product.brand_name }}</td>
                        <td class="px-4 py-1.5 text-center"> {{ product.status }}</td>
                        <td class="text-center">
                            <div class="inline-flex space-x-1">
                                <Link :href="route('admin.products.show', product.id)">
                                    <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path d="M0 0h24v24H0z" stroke="none"/>
                                        <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                                        <line x1="16" x2="19" y1="5" y2="8"/>
                                    </svg>
                                </Link>

                                <button v-if="product.status === 'Active'" @click="destroyProductId = product.id">
                                    <svg class="h-6 w-6 text-red-500" fill="none" height="24" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         viewBox="0 0 24 24"
                                         width="24">
                                        <path d="M0 0h24v24H0z" stroke="none"/>
                                        <line x1="4" x2="20" y1="7" y2="7"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                    </svg>
                                </button>

                                <button v-else @click="restoreProductId = product.id">
                                    <svg class="h-6 w-6 text-green-500" fill="none" height="24" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         viewBox="0 0 24 24"
                                         width="24">
                                        <path d="M0 0h24v24H0z" stroke="none"/>
                                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5"/>
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-7 flex justify-center">
                <TailwindPagination :data="products" :limit="1"
                                    @pagination-change-page="query.page = $event; replaceRoute()"></TailwindPagination>
            </div>
        </div>
    </div>

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

    <Modal :show="exportModal" @close="exportModal = false">
        <div class="p-6">
            <p class="mt-1 text-sm text-gray-600">
                Exporting products will generate a XLSX file with all the filter products in your store.
            </p>
            <div class="flex justify-between mt-6">
                <primary-button class="disabled:bg-amber-500" @click="exportProducts">Export Products</primary-button>

                <div v-if="!exportFileName || exportFileName === 'failed'"></div>
                <div v-else-if="exportFileName === 'pending'" class="flex items-center">
                    <div class="dots-loading flex items-center">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p class="ml-2 inline-block align-middle">Generating</p>
                </div>

                <a v-else :href="exportFileName" class="underline my-auto">Download Export</a>
            </div>
            <InputError :message="exportError" class="mt-3"></InputError>
        </div>
    </Modal>

    <ImportModal :show="showImportModal" @close="showImportModal = false"></ImportModal>
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
