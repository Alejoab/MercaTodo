<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import UpdateInformation from "@/Pages/Administrator/partials/UpdateInformation.vue";
import UpdateAddressInformationAdmin from "@/Pages/Administrator/partials/UpdateAddressInformationAdmin.vue";
import UpdatePasswordAdmin from "@/Pages/Administrator/partials/UpdatePasswordAdmin.vue";
import BackButton from "@/Components/BackButton.vue";
import ActionsAdmin from "@/Pages/Administrator/partials/ActionsAdmin.vue";


defineProps({
    user: {
        type: Object
    },
    role: {
        type: Object
    },
    documentTypes: {
        type: Object
    },
    departments: {
        type: Object
    }
})
</script>

<template>
<AdminLayout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="relative">
                <div class="absolute top-0 right-0">
                    <BackButton :href="route('admin.users')"></BackButton>
                </div>
            </div>
            <UpdateInformation :user="user" :role="role" :documentTypes="documentTypes" class="max-w-xl mt-5 sm:mt-0"/>
        </div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdateAddressInformationAdmin :user="user" :departments="departments" class="max-w-xl"/>
        </div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" >
            <UpdatePasswordAdmin :user="user" class="max-w-xl"/>
        </div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" >
            <ActionsAdmin :user="user" class="max-w-xl"/>
        </div>
    </div>
</AdminLayout>
</template>

