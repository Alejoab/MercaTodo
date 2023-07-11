<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    reportTypes: {
        type: Array,
        required: true,
    },
})

const form = useForm({
    from: null,
    to: null,
    reports: [],
})
</script>

<template>
    <Head><title>Reports</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <h1 class="text-xl font-bold">Generate Reports</h1>
            <form class="mt-7 flex flex-col md:flex-row space-y-10 md:space-y-0">
                <div class="w-full">
                    <div class="flex justify-between w-full">
                        <div>
                            <InputLabel class="mb-3" for="from" value="From"></InputLabel>
                            <input
                                id="from"
                                v-model="form.from"
                                class="rounded-sm border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                name="from"
                                type="date"
                            >
                            <InputError :message="form.errors.from" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel class="mb-3" for="to" value="To"></InputLabel>
                            <input
                                id="to"
                                v-model="form.to"
                                class="rounded-sm border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                name="to"
                                type="date"
                            >
                            <InputError :message="form.errors.to" class="mt-2"/>
                        </div>
                    </div>
                    <div class="mt-10 space-y-2">
                        <InputLabel value="Reports"/>
                        <div v-for="type in reportTypes">
                            <input id="type"
                                   v-model="form.reports"
                                   :value="type"
                                   class="cursor-pointer rounded text-blue-600 border-gray-300"
                                   type="checkbox"
                            />
                            <label class="ml-2.5" for="brand.id">{{ type }}</label>
                        </div>

                        <InputError :message="form.errors.reports" class="mt-2"/>
                    </div>
                </div>
                <div class="w-full text-end">
                    <primary-button>Generate</primary-button>
                </div>
            </form>
        </div>
    </UserLayout>
</template>
