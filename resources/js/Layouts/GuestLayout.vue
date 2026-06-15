<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-surface-alt pt-6 sm:justify-center sm:pt-0"
    >
        <div class="mb-8">
            <Link href="/">
                <ApplicationLogo class="h-16 w-auto fill-current text-primary" />
            </Link>
        </div>

        <div
            class="w-full overflow-hidden bg-white px-8 py-10 shadow-soft-xl sm:max-w-md sm:rounded-3xl border border-gray-100"
        >
            <slot />
        </div>
        
        <div class="mt-8 text-sm text-content-light">
            &copy; 2026 PAKID - Pameran Karya Inovasi Digital
        </div>
    </div>
</template>
