<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Selamat Datang di PAKID" />
    
    <div class="min-h-screen bg-surface-alt selection:bg-primary selection:text-white">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-18 items-center">
                    <div class="flex items-center gap-2">
                        <ApplicationLogo class="h-10 w-auto fill-current text-primary" />
                        <span class="text-xl font-bold text-content tracking-tight">PAKID</span>
                    </div>
                    
                    <div v-if="canLogin" class="flex items-center gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="text-sm font-semibold text-content hover:text-primary transition-colors"
                        >
                            Masuk ke Pameran
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm font-semibold text-content hover:text-primary transition-colors"
                            >
                                Masuk
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-2 text-sm font-semibold text-white hover:bg-primary-dark transition-all"
                            >
                                Daftar
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main>
            <section class="relative pt-20 pb-32 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center max-w-3xl mx-auto">
                        <h1 class="text-5xl sm:text-6xl font-extrabold text-content tracking-tight leading-tight">
                            Wadah <span class="text-primary italic">Inovasi </span> Digital Masa Depan
                        </h1>
                        <p class="mt-6 text-xl text-content-light leading-relaxed">
                            PAKID (Pameran Karya Inovasi Digital) adalah platform untuk menampilkan, mengapresiasi, dan mengembangkan ide-ide kreatif mahasiswa dalam dunia teknologi.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                            <Link :href="route('dashboard')">
                                <PrimaryButton class="px-8 py-4 text-base rounded-2xl w-full sm:w-auto">
                                    Jelajahi Karya
                                </PrimaryButton>
                            </Link>
                            <Link v-if="!$page.props.auth.user" :href="route('register')" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-content bg-white rounded-2xl border border-gray-200 shadow-soft hover:bg-surface-alt transition-all w-full sm:w-auto">
                                Gabung Sebagai Peserta
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Background Decorations -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-0 pointer-events-none opacity-20">
                    <div class="absolute top-20 left-10 w-72 h-72 bg-primary rounded-full blur-[120px]"></div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-300 rounded-full blur-[140px]"></div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="bg-white py-20 border-y border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                        <div>
                            <div class="text-4xl font-bold text-content">100+</div>
                            <div class="text-content-light mt-2 font-medium">Karya Terdaftar</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-content">500+</div>
                            <div class="text-content-light mt-2 font-medium">Pengunjung Aktif</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-content">10+</div>
                            <div class="text-content-light mt-2 font-medium">Kategori Inovasi</div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="py-12 bg-white text-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center gap-6">
                    <ApplicationLogo class="h-10 w-auto fill-current text-primary opacity-50" />
                    <p class="text-sm text-content-light font-medium">
                        &copy; 2026 PAKID. Dibuat dengan dedikasi untuk inovasi mahasiswa.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.h-18 { height: 4.5rem; }
</style>
