<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    reportTypes: {
        type: Array,
        required: true,
    },
});

const isLoading = ref(false);
const reportFile = ref('');
let pollingInterval = null;

const form = useForm({
    from: null,
    to: null,
    reports: [],
});

const submit = () => {
    isLoading.value = true;

    form.post(route('admin.reports.generate'), {
        preserveScroll: true,
        onSuccess: () => {
            pollingInterval = setInterval(() => checkReport(), 3000);
        },
        onError: () => {
            isLoading.value = false;
        }
    });
}

const checkReport = async () => {
    const response = await axios.get(route('admin.reports.check'));

    if (response.data.length === 0) {
        clearInterval(pollingInterval);
        isLoading.value = false;
        return true;
    }

    if (response.data.status === 'Completed') {
        clearInterval(pollingInterval);
        isLoading.value = false;
        reportFile.value = route('admin.reports.download');
        return true;
    }

    if (response.data.status === 'Failed') {
        clearInterval(pollingInterval);
        isLoading.value = false;
        form.errors.report = 'An error has occurred in the report. Please try again later';
        return true;
    }

    return false;
}

const initialPolling = async () => {
    if (!await checkReport()) {
        isLoading.value = true;
        pollingInterval = setInterval(() => checkReport(), 3000);
    }
}

onMounted(() => {
    initialPolling();
})

onUnmounted(() => {
    clearInterval(pollingInterval);
});
</script>

<template>
    <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
        <h1 class="text-xl font-bold">Generate Reports</h1>
        <form class="mt-7 flex flex-col md:flex-row space-y-10 md:space-y-0" @submit.prevent="submit">
            <div class="w-full">
                <div class="grid grid-cols-2 w-full">
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
                <primary-button v-if="!isLoading">Generate</primary-button>
                <primary-button v-else disabled="true">
                    <svg aria-hidden="true" class="inline w-4 h-4 mr-3 text-white animate-spin" fill="none"
                         role="status" viewBox="0 0 100 101" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="#1C64F2"/>
                    </svg>
                    Loading
                </primary-button>
                <InputError :message="form.errors.report" class="mt-5"></InputError>
                <p class="mt-5"><a v-if="reportFile" :href="reportFile" class="underline my-auto">Download Export</a>
                </p>
            </div>
        </form>
    </div>
</template>
