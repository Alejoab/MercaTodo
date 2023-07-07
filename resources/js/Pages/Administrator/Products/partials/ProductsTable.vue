<script setup>
import {TailwindPagination} from "laravel-vue-pagination";
import {Link, router, usePage} from '@inertiajs/vue3';
import {ref} from "vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import ExportModal from "@/Pages/Administrator/Products/partials/ExportModal.vue";
import ImportModal from "@/Pages/Administrator/Products/partials/ImportModal.vue";

const props = defineProps({
    products: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    brands: {
        type: Array,
        required: true
    },
});

const brands = ref(props.brands);
const destroyProductId = ref('');
const restoreProductId = ref('');
const showExportModal = ref(false);
const showImportModal = ref(false);

const query = ref({
    page: usePage().props.ziggy.query['page'] ? usePage().props.ziggy.query['page'] : 1,
    search: usePage().props.ziggy.query['search'] ? usePage().props.ziggy.query['search'] : '',
    category: usePage().props.ziggy.query['category'] ? usePage().props.ziggy.query['category'] : '',
    brand: usePage().props.ziggy.query['brand'] ? usePage().props.ziggy.query['brand'] : '',
});

const getProducts = async (page = 1) => {
    query.value.page = page;
    await router.visit(route('admin.products', query.value), {
        preserveScroll: true,
        replace: true,
    });
}

const clearQuery = async () => {
    await router.visit(route('admin.products'), {
        preserveScroll: true,
        replace: true,
    });
}

const getBrands = async () => {
    const response = await fetch(route('brands', query.value.category));
    brands.value = await response.json();
}

const destroyProduct = (id) => {
    destroyProductId.value = '';
    axios.delete(route('admin.products.destroy', id))
        .then(() => {
            const updateProduct = props.products.data.find(product => product.id === id);
            if (updateProduct) {
                updateProduct.status = updateProduct.status === 'Active' ? 'Inactive' : 'Active';
            }
        });
}

const restoreProduct = (id) => {
    restoreProductId.value = '';
    axios.put(route('admin.products.restore', id))
        .then(() => {
            const updateProduct = props.products.data.find(product => product.id === id);
            if (updateProduct) {
                updateProduct.status = updateProduct.status === 'Active' ? 'Inactive' : 'Active';
            }
        });
}
</script>

<template>
    <div>
        <div class="flex flex-col lg:flex-row xl:pl-10 justify-between mt-5">
            <div class="flex flex-col md:flex-row my-auto md:space-x-4 space-y-3 md:space-y-0 mx-auto">
                <div>
                    <input
                        id="simple-search"
                        v-model="query.search"
                        class="bg-gray-50 border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5"
                        placeholder="Search"
                        type="text"
                    >
                </div>

                <div>
                    <select
                        id="category_name"
                        v-model="query.category"
                        autocomplete="category_name"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-60"
                        @change="getBrands(); query.brand = '';"
                    >
                        <option disabled selected value="">Select a Category</option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>

                <div>
                    <select
                        id="brand_name"
                        v-model="query.brand"
                        autocomplete="brand_name"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-60"
                    >
                        <option disabled selected value="">Select a Brand</option>
                        <option v-for="brand in brands" :value="brand.id">{{ brand.name }}</option>
                    </select>
                </div>
            </div>

            <div class="w-full flex justify-between mt-5 lg:mt-0 px-10 lg:px-0">
                <div class="my-auto">
                    <button class="p-1 ml-2" @click="getProducts()">
                        <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor"
                             stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0z" stroke="none"/>
                            <circle cx="10" cy="10" r="7"/>
                            <line x1="21" x2="15" y1="21" y2="15"/>
                        </svg>
                    </button>

                    <button class="p-1 ml-2" @click="clearQuery()">
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
                                <button class="text-start p-2 pl-3" @click="showExportModal = true">Export Products
                                </button>
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
                                    @pagination-change-page="getProducts()"></TailwindPagination>
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

    <ExportModal :query="query" :show="showExportModal" @close="showExportModal = false"></ExportModal>

    <ImportModal :show="showImportModal" @close="showImportModal = false"></ImportModal>
</template>
