<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    karyas: Array,
});
</script>

<template>
    <Head title="Manajemen Karya Saya" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Galeri Saya</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Kelola dan pantau karya inovasi yang Anda unggah.</p>
                </div>
                <Link :href="route('peserta.upload')">
                    <PrimaryButton class="px-6 py-3 rounded-2xl shadow-soft-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Unggah Karya Baru
                    </PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            
            <!-- Grid Daftar Karya -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="karya in karyas" :key="karya.id" class="group bg-white rounded-[2rem] shadow-soft border border-gray-100 overflow-hidden hover:shadow-soft-xl transition-all duration-500">
                    <div class="relative h-56 overflow-hidden">
                        <img :src="'/storage/' + karya.file_multimedia" alt="Thumbnail" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                    
                    <div class="p-8">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                            {{ karya.kategori.nama_kategori }}
                        </span>
                        <h3 class="text-xl font-bold text-content mt-4 mb-2 group-hover:text-primary transition-colors line-clamp-1">{{ karya.judul_karya }}</h3>
                        
                        <div class="flex items-center gap-4 text-xs font-bold text-content-light mb-6">
                            <span class="flex items-center gap-1.5"><span class="text-red-500">❤️</span> {{ karya.votes_count }} Suka</span>
                            <span class="flex items-center gap-1.5"><span class="text-primary">💬</span> {{ karya.komentars_count }} Diskusi</span>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex gap-2">
                                <Link :href="route('karya.edit', karya.id)" class="flex-1">
                                    <SecondaryButton class="w-full text-xs py-2 rounded-xl">
                                        Edit Karya
                                    </SecondaryButton>
                                </Link>
                                <Link
                                    :href="route('peserta.karya.destroy', karya.id)"
                                    method="delete"
                                    as="button"
                                    preserve-scroll
                                    class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white text-xs font-bold rounded-xl transition-all border border-red-100"
                                    @click="(event) => {
                                        if (!confirm('Yakin ingin menghapus karya ini secara permanen?')) event.preventDefault();
                                    }"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </Link>
                            </div>
                            <Link :href="route('karya.show', karya.id)">
                                <PrimaryButton class="w-full text-xs py-2 rounded-xl bg-surface-alt !text-content hover:!bg-gray-200">
                                    Pratinjau Detail &rarr;
                                </PrimaryButton>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="karyas.length === 0" class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-soft-xl border border-gray-100">
                <div class="w-32 h-32 bg-surface-alt rounded-full flex items-center justify-center mb-6 text-gray-200">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-content">Belum Ada Karya</h3>
                <p class="text-content-light mt-2 font-medium">Mulai tunjukkan inovasi Anda kepada dunia.</p>
                <Link :href="route('peserta.upload')" class="mt-8">
                    <PrimaryButton class="px-8 py-3 rounded-2xl">
                        Unggah Karya Pertama
                    </PrimaryButton>
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
