<script setup>
import { watch, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');

watch(() => page.props.flash.message, (newVal) => {
    if (newVal) {
        message.value = newVal;
        show.value = true;
        setTimeout(() => {
            show.value = false;
        }, 5000);
    }
}, { immediate: true });
</script>

<template>
    <div v-if="show" class="fixed bottom-10 right-10 z-[100] transform transition-all duration-500 ease-in-out">
        <div class="bg-content text-white px-8 py-4 rounded-[1.5rem] shadow-soft-xl border border-white/10 flex items-center gap-4 animate-bounce-short">
            <div class="w-8 h-8 bg-primary rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="font-bold text-sm tracking-tight">{{ message }}</p>
            <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
</template>

<style scoped>
.animate-bounce-short {
    animation: bounce 0.5s ease-out;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
</style>
