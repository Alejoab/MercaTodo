<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";
import GenerateReports from "@/Pages/Administrator/Reports/GenerateReports.vue";
import GenerateSales from "@/Pages/Administrator/Reports/GenerateSales.vue";

const props = defineProps({
    reportTypes: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Head><title>Reports</title></Head>
    <UserLayout>
        <GenerateReports :report-types="reportTypes"></GenerateReports>
        <GenerateSales class="mt-10"></GenerateSales>
    </UserLayout>
</template>
