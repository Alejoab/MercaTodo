<script setup>
import {Head} from "@inertiajs/vue3";
import UsersTable from "@/Pages/Administrator/Users/partials/UsersTable.vue";
import UserLayout from "@/Layouts/UserLayout.vue";

const props = defineProps({
    'users': {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <Head><title>Users</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto lg:p-8 md:p-8 p-4 bg-white shadow sm:rounded-lg">
            <UsersTable :users="users"></UsersTable>
        </div>
    </UserLayout>
</template>
