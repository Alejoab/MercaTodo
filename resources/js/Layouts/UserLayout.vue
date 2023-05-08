<script setup>
import UserNavBar from "@/Components/UserNavBar.vue";
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Responsive Navigation Menu -->
        <UserNavBar></UserNavBar>

        <!-- Page Content -->
        <main v-if="$page.props.auth.user" class="py-12">
            <slot/>
        </main>
    </div>
</template>
