<script setup>
import UpdateInformation from "@/Pages/Administrator/Users/partials/UpdateInformation.vue";
import UpdatePasswordAdmin from "@/Pages/Administrator/Users/partials/UpdatePasswordAdmin.vue";
import ActionsAdmin from "@/Pages/Administrator/Users/partials/ActionsAdmin.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";

defineProps({
    user: {
        type: Object
    },
    roles: {
        type: Object
    },
    permissions_view: {
        type: Object
    }
})
</script>

<template>
    <Head><title>Edit User</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdateInformation :permissions="permissions_view" :roles="roles" :user="user"
                                   class="max-w-xl sm:mt-0 mt-16"/>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdatePasswordAdmin :user="user" class="max-w-xl"/>
            </div>
            <div v-if="usePage().props.permissions.includes('Delete')" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <ActionsAdmin :user="user" class="max-w-xl"/>
            </div>
        </div>
    </UserLayout>
</template>

