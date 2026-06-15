<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    karyas: Object,
});
</script>

<template>
    <Head title="Galeri Pameran Digital" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Galeri Inovasi</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Jelajahi ide-ide kreatif dari para inovator muda.</p>
                </div>
                <div v-if="$page.props.auth.user.role === 'peserta'">
                    <Link :href="route('peserta.upload')" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-soft-xl hover:bg-primary-dark transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Unggah Karya Baru
                    </Link>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            
            <!-- Grid Card Interface -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="karya in karyas.data" 
                    :key="karya.id" 
                    class="group bg-white rounded-[2rem] shadow-soft border border-gray-100 flex flex-col hover:shadow-soft-xl transition-all duration-500 overflow-hidden"
                >
                    <!-- Thumbnail Area -->
                    <div class="relative h-64 overflow-hidden">
                        <img 
                            :src="'/storage/' + karya.file_multimedia" 
                            :alt="karya.judul_karya" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        >
                        <div class="absolute top-5 left-5">
                            <span class="px-4 py-1.5 bg-white/90 backdrop-blur-sm text-primary text-[10px] font-black uppercase tracking-widest rounded-full shadow-soft border border-white">
                                {{ karya.kategori.nama_kategori }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="p-8 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-content leading-tight group-hover:text-primary transition-colors">{{ karya.judul_karya }}</h3>
                        <div class="flex items-center mt-3 gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-bold text-primary">
                                {{ karya.user.name.charAt(0) }}
                            </div>
                            <p class="text-xs font-semibold text-content-light uppercase tracking-wider">Oleh {{ karya.user.name }}</p>
                        </div>
                        
                        <p class="text-content-light mt-5 line-clamp-3 text-sm leading-relaxed flex-grow">
                            {{ karya.deskripsi_inovasi }}
                        </p>
                        
                        <!-- Interactions -->
                        <div class="mt-8 pt-6 border-t border-gray-50 flex justify-between items-center">
                            <Link 
                                :href="route('karya.vote', karya.id)" 
                                method="post" 
                                preserve-scroll
                                class="flex items-center gap-2 group/vote"
                            >
                                <div :class="[
                                    'w-10 h-10 rounded-xl flex items-center justify-center transition-all',
                                    karya.votes.length > 0 ? 'bg-red-50 text-red-500 shadow-sm' : 'bg-surface-alt text-content-light group-hover/vote:bg-red-50 group-hover/vote:text-red-500'
                                ]">
                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        class="h-5 w-5 transition-transform group-hover/vote:scale-110" 
                                        :fill="karya.votes.length > 0 ? 'currentColor' : 'none'" 
                                        viewBox="0 0 24 24" 
                                        stroke="currentColor"
                                        stroke-width="2.5"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-content">
                                    {{ karya.votes_count }} <span class="text-content-light font-medium ml-0.5">Suka</span>
                                </span>
                            </Link>
                            
                            <Link :href="route('karya.show', karya.id)" class="text-xs font-black text-primary uppercase tracking-widest hover:translate-x-1 transition-transform">
                                Detail &rarr;
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center gap-2 p-2 bg-white rounded-3xl shadow-soft border border-gray-100">
                    <template v-for="(link, p) in karyas.links" :key="p">
                        <div v-if="link.url === null" 
                            class="px-4 py-2 text-content-light opacity-50 text-sm font-bold pointer-events-none" 
                            v-html="link.label">
                        </div>
                        <Link v-else 
                            :href="link.url" 
                            class="px-4 py-2 rounded-2xl text-sm font-bold transition-all"
                            :class="link.active ? 'bg-primary text-white shadow-soft' : 'text-content hover:bg-surface-alt'"
                            v-html="link.label">
                        </Link>
                    </template>
                </nav>
            </div>

            <!-- Empty State -->
            <div v-if="karyas.data.length === 0" class="flex flex-col items-center justify-center py-32 bg-white rounded-[3rem] shadow-soft-xl border border-gray-100">
                <div class="w-32 h-32 bg-surface-alt rounded-full flex items-center justify-center mb-6">
                    <svg class="h-16 w-16 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-content">Etalase Masih Kosong</h3>
                <p class="text-content-light mt-2 font-medium">Belum ada karya yang diunggah untuk saat ini.</p>
                <Link v-if="$page.props.auth.user.role === 'peserta'" :href="route('peserta.upload')" class="mt-8 text-primary font-bold hover:underline underline-offset-8">Mulai Unggah Karya Pertama Anda &rarr;</Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
