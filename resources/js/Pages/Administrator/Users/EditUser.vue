<script setup>
import UpdateInformation from "@/Pages/Administrator/Users/partials/UpdateInformation.vue";
import UpdatePasswordAdmin from "@/Pages/Administrator/Users/partials/UpdatePasswordAdmin.vue";
import BackButton from "@/Components/BackButton.vue";
import ActionsAdmin from "@/Pages/Administrator/Users/partials/ActionsAdmin.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import {Head} from "@inertiajs/vue3";


defineProps({
    user: {
        type: Object
    },
    roles: {
        type: Object
    }
})
</script>

<template>
    <Head><title>Edit User</title></Head>
    <UserLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="text-end pb-3 md:pb-0">
                    <BackButton :href="route('admin.users')" ></BackButton>
                </div>
                <UpdateInformation :user="user" :roles="roles" class="max-w-xl mt-5 sm:mt-0 mt-16"/>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" >
                <UpdatePasswordAdmin :user="user" class="max-w-xl"/>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" >
                <ActionsAdmin :user="user" class="max-w-xl"/>
            </div>
        </div>
    </UserLayout>
</template>

