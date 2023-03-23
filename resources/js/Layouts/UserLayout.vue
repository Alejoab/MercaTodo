<script setup>
import UserNavLink from "@/Components/UserNavLink.vue";
</script>

<template>

        <div class="min-h-screen bg-gray-100">
            <!-- Responsive Navigation Menu -->
            <UserNavLink></UserNavLink>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

</template>
