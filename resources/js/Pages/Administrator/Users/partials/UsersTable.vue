<script setup>
import {TailwindPagination} from "laravel-vue-pagination";
import {Link, router, usePage} from '@inertiajs/vue3';
import {ref} from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

const props = defineProps({
    'users': {
        type: Object,
        required: true,
    },
})

const search = ref('');
const destroyUserId = ref('');
const restoreUserId = ref('');


const getUsers = async (page = 1, search = '') => {
    await router.visit(route('admin.users', {page: page, search: search}), {
        preserveScroll: true,
        replace: true,
    });
}

const destroyUser = (id) => {
    destroyUserId.value = '';
    axios.delete(route('admin.user.destroy', id))
        .then(() => {
            const updatedUser = props.users.data.find(user => user.id === id);
            if (updatedUser) {
                updatedUser.deleted = updatedUser.deleted === 'Active' ? 'Inactive' : 'Active';
            }
        })
}

const restoreUser = async (id) => {
    restoreUserId.value = '';
    axios.put(route('admin.user.restore', id))
        .then(() => {
            const updatedUser = props.users.data.find(user => user.id === id);
            if (updatedUser) {
                updatedUser.deleted = updatedUser.deleted === 'Active' ? 'Inactive' : 'Active';
            }
        })
}
</script>

<template>
    <div>
        <div class="mb-8 xl:ml-10">
            <div class="flex">
                <label class="sr-only" for="simple-search">Search</label>
                <div class="relative">
                    <input id="simple-search" v-model="search"
                           class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 pl-4 p-2.5"
                           placeholder="Search" required type="text">
                </div>
                <button class="p-1 ml-2" @click="getUsers(1, search)">
                    <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" stroke="none"/>
                        <circle cx="10" cy="10" r="7"/>
                        <line x1="21" x2="15" y1="21" y2="15"/>
                    </svg>
                </button>
                <button class="p-1 ml-2" @click="getUsers(1); search = ''">
                    <svg class="h-8 w-8 text-black" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" stroke="none"/>
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5"/>
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5"/>
                    </svg>
                </button>
            </div>
        </div>
        <div>
            <div class="overflow-auto">
                <table class="w-full text-xs md:text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50">
                    <th class="px-6 py-3" scope="col"> User ID</th>
                    <th class="px-6 py-3" scope="col"> Email</th>
                    <th class="px-6 py-3" scope="col"> Role</th>
                    <th class="px-6 py-3" scope="col"> Status</th>
                    <th v-if="usePage().props.permissions.includes('Update') || usePage().props.permissions.includes('Delete')" class="px-6 py-3 text-center"
                        scope="col">
                        Actions
                    </th>
                    </thead>
                    <tbody>
                    <tr v-for="user in users.data" class="bg-white border-b">

                        <th class="px-6 py-1.5 font-medium whitespace-nowrap" scope="row"> {{ user.id }}</th>
                        <td class="px-6 py-1.5"> {{ user.email }}</td>
                        <td class="px-6 py-1.5"> {{ user.role }}</td>
                        <td class="px-6 py-1.5"> {{ user.deleted }}</td>
                        <td v-if="usePage().props.permissions.includes('Update') || usePage().props.permissions.includes('Delete')"
                            class="px-6 py-1.5 text-center">
                            <div class="inline-flex space-x-1">
                                <Link v-if="usePage().props.permissions.includes('Update')"
                                      :href="route('admin.user.show', user.id)">
                                    <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path d="M0 0h24v24H0z" stroke="none"/>
                                        <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                                        <line x1="16" x2="19" y1="5" y2="8"/>
                                    </svg>
                                </Link>
                                <button
                                    v-if="user.deleted === 'Active' && usePage().props.permissions.includes('Delete')"
                                    @click="destroyUserId = user.id">
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
                                <button
                                    v-if="user.deleted !== 'Active' && usePage().props.permissions.includes('Delete')"
                                    @click="restoreUserId = user.id">
                                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="8.5" cy="7" r="4"/>
                                        <line x1="20" x2="20" y1="8" y2="14"/>
                                        <line x1="23" x2="17" y1="11" y2="11"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-7 flex justify-center">
                <TailwindPagination :data="users" :limit="1" @pagination-change-page="getUsers"></TailwindPagination>
            </div>
        </div>
    </div>

    <Modal :show="!! destroyUserId" @close="destroyUserId = ''">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete the account?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once you delete this account, it will only be disabled for the user, but the information will still be
                stored.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="destroyUserId = ''"> Cancel</SecondaryButton>

                <DangerButton
                    class="ml-3"
                    @click="destroyUser(destroyUserId)"
                >
                    Delete Account
                </DangerButton>
            </div>
        </div>
    </Modal>

    <Modal :show="!! restoreUserId" @close="restoreUserId = ''">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to restore the account?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once you restore the account the user will have access to it again.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="restoreUserId = ''"> Cancel</SecondaryButton>

                <SuccessButton
                    class="ml-3"
                    @click="restoreUser(restoreUserId)"
                >
                    Restore Account
                </SuccessButton>
            </div>
        </div>
    </Modal>
</template>
