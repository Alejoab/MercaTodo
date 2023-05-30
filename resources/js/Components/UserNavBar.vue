<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {Link, usePage} from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import {ref} from "vue";
import SearchBox from "@/Components/SearchBox.vue";
import ShoppingCart from "@/Components/ShoppingCart.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex w-full">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('home')">
                            <ApplicationLogo
                                class="block h-9 w-auto fill-current text-gray-800"
                            />
                        </Link>
                    </div>

                    <div v-if="!usePage().props.ziggy.location.includes('admin')" class="w-[60%] flex m-auto">
                        <search-box></search-box>
                    </div>
                </div>

                <div class="hidden lg:flex lg:items-center lg:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="mr-6">
                        <div v-if="$page.props.auth.user" class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            type="button">
                                            {{ $page.props.auth.user.email }}
                                            <svg class=" -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path clip-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      fill-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <div v-if="$page.props.isAdmin">
                                        <DropdownLink :href="route('admin')"> Administrator</DropdownLink>
                                        <DropdownLink :href="route('admin.users')" class="pl-8 flex">
                                            <div class="border border-indigo-100 mr-3"></div>
                                            Users
                                        </DropdownLink>
                                        <DropdownLink :href="route('admin.customers')" class="pl-8 flex">
                                            <div class="border border-indigo-100 mr-3"></div>
                                            Customers
                                        </DropdownLink>
                                        <DropdownLink :href="route('admin.products')" class="pl-8 flex">
                                            <div class="border border-indigo-100 mr-3"></div>
                                            Products
                                        </DropdownLink>
                                    </div>
                                    <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                    <DropdownLink :href="route('order.history')"> Order History</DropdownLink>
                                    <DropdownLink :href="route('logout')" as="button" method="post">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <div v-else class="ml-3 relative">
                            <div class="whitespace-nowrap">
                                <Link
                                    :href="route('login')"
                                    class="font-semibold text-gray-400 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Log in
                                </Link>

                                <Link
                                    :href="route('register')"
                                    class="ml-4 font-semibold text-gray-400 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Register
                                </Link
                                >
                            </div>
                        </div>
                    </div>

                </div>

                <div v-if="!usePage().props.ziggy.location.includes('admin')" class="flex items-center">
                    <!-- Shopping Carts -->
                    <ShoppingCart></ShoppingCart>
                </div>

                <!-- Hamburger -->
                <div class="ml-3 -mr-2 flex items-center lg:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                d="M4 6h16M4 12h16M4 18h16"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                            <path
                                :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                d="M6 18L18 6M6 6l12 12"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden"
        >
            <!-- Responsive Settings Options -->
            <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">
                        {{ $page.props.auth.user.email }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink v-if="$page.props.isAdmin" :href="route('admin')"> Administrator</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('order.history')"> Order History</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" as="button" method="post">Log Out</ResponsiveNavLink>
                </div>
            </div>

            <div v-else class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('register')"> Register</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('login')"> Log In</ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
