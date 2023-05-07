<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const categories = ref([]);
const brands = ref([]);
const image = ref(null);
let delayTimer = null;

const form = useForm({
    code: '',
    name: '',
    description: '',
    price: '',
    stock: '',

    category_name: '',
    brand_name: '',

    image: null,
});

const selectCategory = (category) => {
    form.category_name = category;
    categories.value = [];
}

const searchCategories = async () => {
    clearTimeout(delayTimer);

    if (form.category_name.length >= 2) {
        delayTimer = setTimeout(async () => {
            let response = await fetch(route('admin.categories.search', {search: form.category_name}));
            categories.value = await response.json();
        }, 200);
    } else {
        categories.value = [];
    }
}

const selectBrand = (brand) => {
    form.brand_name = brand;
    brands.value = [];
}

const searchBrands = async () => {
    clearTimeout(delayTimer);

    if (form.brand_name.length >= 2) {
        delayTimer = setTimeout(async () => {
            let response = await fetch(route('admin.brands.search', {search: form.brand_name}));
            brands.value = await response.json();
        }, 200);
    } else {
        brands.value = [];
    }
}

const uploadImage = (e) => {
    form.image = e.target.files[0];
    let file = e.target.files[0];
    let reader = new FileReader();

    reader.onloadend = (file) => {
        image.value = reader.result;
    }
    reader.readAsDataURL(file);
}

const isNumber = (evt) => {
    const keysAllowed = new Set(['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.']);
    const keyPressed = evt.key;

    if (!keysAllowed.has(keyPressed)) {
        evt.preventDefault()
    }
}
</script>


<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Create New Product</h2>

            <p class="mt-1 text-sm text-gray-600">
                Complete the product information
            </p>
        </header>

        <form @submit.prevent="form.post(route('admin.products.create'))">
            <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 mt-6">
                <div class="space-y-6">
                    <div>
                        <InputLabel for="code" value="Code"/>

                        <TextInput
                            id="code"
                            v-model="form.code"
                            autocomplete="code"
                            autofocus
                            class="mt-1 block w-full"
                            maxlength="6"
                            required
                            type="text"
                        />

                        <InputError :message="form.errors.code" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="name" value="Name"/>

                        <TextInput
                            id="name"
                            v-model="form.name"
                            autocomplete="name"
                            autofocus
                            class="mt-1 block w-full"
                            required
                            type="text"
                        />

                        <InputError :message="form.errors.name" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="description" value="Description"/>

                        <textarea
                            id="description"
                            v-model="form.description"
                            autocomplete="description"
                            autofocus
                            class="mt-1 block w-full whitespace-normal resize-none h-40 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            type="text"
                        />

                        <InputError :message="form.errors.description" class="mt-2"/>
                    </div>

                    <div class="relative">
                        <InputLabel for="category_name" value="Category"/>

                        <TextInput
                            id="category_name"
                            v-model="form.category_name"
                            :onkeyup="searchCategories"
                            autocomplete="category_name"
                            autofocus
                            class="mt-1 block w-full"
                            required
                            type="text"
                            @blur="categories = []"
                        />

                        <ul v-show="categories.length"
                            class="bg-gray-100 mt-0.5 w-full absolute border border-gray-300 rounded overflow-y-auto max-h-32 z-50 shadow-sm">
                            <li
                                v-for="category in categories"
                                class="hover:bg-blue-300 pl-3 py-1"
                                v-on:mousedown="selectCategory(category.name)"
                            >
                                {{ category.name }}
                            </li>
                        </ul>

                        <InputError :message="form.errors.category_name" class="mt-2"/>
                    </div>

                    <div class="relative">
                        <InputLabel for="brand_name" value="Brand"/>

                        <TextInput
                            id="brand_name"
                            v-model="form.brand_name"
                            :onkeyup="searchBrands"
                            autocomplete="brand_name"
                            autofocus
                            class="mt-1 block w-full"
                            required
                            type="text"
                            @blur="brands = []"
                        />

                        <ul v-show="brands.length"
                            class="bg-gray-100 mt-0.5 w-full absolute border border-gray-300 rounded overflow-y-auto max-h-32 z-50 shadow-sm">
                            <li
                                v-for="brand in brands"
                                class="hover:bg-blue-300 pl-3 py-1"
                                v-on:mousedown="selectBrand(brand.name)"
                            >
                                {{ brand.name }}
                            </li>
                        </ul>

                        <InputError :message="form.errors.category_name" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="price" value="Price"/>

                        <TextInput
                            id="price"
                            v-model="form.price"
                            autocomplete="price"
                            autofocus
                            class="mt-1 block w-full"
                            required
                            type="text"
                            @keypress="isNumber"
                        />

                        <InputError :message="form.errors.price" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="stock" value="Stock"/>

                        <TextInput
                            id="stock"
                            v-model="form.stock"
                            autocomplete="stock"
                            autofocus
                            class="mt-1 block w-full"
                            required
                            type="number"
                        />

                        <InputError :message="form.errors.stock" class="mt-2"/>
                    </div>
                </div>

                <div>
                    <InputLabel for="image" value="Please select an image"/>
                    <input id="image" accept="image/x-png, image/jpeg" class="mt-2.5" name="Product Image"
                           type="file" @input="uploadImage">
                    <InputError :message="form.errors.image" class="mt-2"/>
                    <div v-show="image" class="mt-5">
                        <img :src="image" alt="Product Image"/>
                    </div>
                </div>
                <div class="grid-cols-2">
                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                </div>
            </div>
        </form>
    </section>
</template>
