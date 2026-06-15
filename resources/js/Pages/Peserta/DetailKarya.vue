<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    karya: Object,
});

const form = useForm({
    isi_komentar: '',
});

const page = usePage();

const is_video = computed(() => {
    const ext = props.karya.file_multimedia?.split('.').pop().toLowerCase();
    return ['mp4', 'webm'].includes(ext);
});

const userRole = computed(() => page.props.auth?.user?.role ?? 'pengunjung');

const backSource = computed(() => {
    const currentUrl = page.url ?? '';
    try {
        const url = new URL(currentUrl, window.location.origin);
        return url.searchParams.get('from');
    } catch {
        return null;
    }
});

const kembaliUrl = computed(() => {
    if (backSource.value === 'leaderboard') return route('leaderboard');
    if (userRole.value === 'admin') return route('admin.dashboard');
    if (userRole.value === 'peserta') return route('peserta.galeri');
    return route('dashboard');
});

const submitKomentar = () => {
    form.post(route('karya.komentar.store', props.karya.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head :title="karya.judul_karya" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="kembaliUrl" class="w-10 h-10 rounded-xl bg-white border border-gray-100 shadow-soft flex items-center justify-center text-content hover:text-primary transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </Link>
                    <h2 class="font-bold text-2xl text-content tracking-tight">Detail Inovasi</h2>
                </div>
                <div class="hidden sm:block">
                    <span class="px-4 py-2 bg-blue-50 text-primary text-xs font-bold rounded-full border border-blue-100 uppercase tracking-widest">
                        Preview Mode
                    </span>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 space-y-10">
            <!-- Media & Info Card -->
            <div class="bg-white rounded-[3rem] shadow-soft-xl border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <!-- Image/Video Section -->
                    <div class="lg:col-span-7 h-[400px] lg:h-[600px] bg-surface-alt relative overflow-hidden group">
                        <video 
                            v-if="is_video"
                            :src="'/storage/' + karya.file_multimedia"
                            class="w-full h-full object-cover"
                            controls
                            autoplay
                            muted
                            loop
                        ></video>
                        <img
                            v-else
                            :src="'/storage/' + karya.file_multimedia"
                            :alt="karya.judul_karya"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>

                    <!-- Info Section -->
                    <div class="lg:col-span-5 p-8 md:p-12 flex flex-col">
                        <div class="mb-8 flex flex-wrap items-center gap-3">
                            <span class="px-4 py-1.5 bg-blue-50 text-primary text-[10px] font-black uppercase tracking-widest rounded-full border border-blue-100">
                                {{ karya.kategori.nama_kategori }}
                            </span>
                            <div class="flex items-center gap-1.5 px-4 py-1.5 bg-red-50 text-red-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-red-100">
                                ❤️ {{ karya.votes_count }} Suka
                            </div>
                        </div>

                        <h1 class="text-3xl font-extrabold text-content leading-tight mb-4">{{ karya.judul_karya }}</h1>
                        
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-[10px] font-bold text-white shadow-soft">
                                {{ karya.user.name.charAt(0) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-content-light uppercase tracking-widest leading-none">Inovator</p>
                                <p class="text-sm font-bold text-content mt-1">{{ karya.user.name }}</p>
                            </div>
                        </div>

                        <div class="space-y-6 flex-grow overflow-y-auto max-h-[300px] pr-4 scrollbar-hide">
                            <p class="text-content-light text-base leading-relaxed whitespace-pre-line">
                                {{ karya.deskripsi_inovasi }}
                            </p>
                        </div>

                        <div class="mt-10 space-y-3">
                            <Link
                                :href="route('karya.vote', karya.id)"
                                method="post"
                                preserve-scroll
                                class="w-full"
                            >
                                <button 
                                    class="w-full flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-bold transition-all border-2"
                                    :class="karya.votes.length > 0 ? 'bg-red-500 text-white border-red-500 shadow-soft-xl' : 'bg-white text-content border-gray-100 hover:border-red-500 hover:text-red-500'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="karya.votes.length > 0 ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ karya.votes.length > 0 ? 'Batal Suka' : 'Sukai Karya Ini' }}
                                </button>
                            </Link>

                            <a v-if="karya.link_prototype" :href="karya.link_prototype" target="_blank" class="w-full inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-bold bg-primary text-white shadow-soft-xl hover:bg-primary-dark transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                Lihat Prototype
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discussion Section -->
            <div class="bg-white rounded-[3rem] shadow-soft-xl border border-gray-100 p-8 md:p-12">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h3 class="text-2xl font-bold text-content tracking-tight">Ruang Diskusi</h3>
                        <p class="text-content-light font-medium text-sm mt-1">Berikan masukan atau apresiasi untuk inovasi ini.</p>
                    </div>
                    <span class="px-5 py-2 bg-surface-alt text-content font-black text-sm rounded-2xl border border-gray-100">
                        {{ karya.komentars.length }} Komentar
                    </span>
                </div>

                <!-- Comment Form -->
                <form @submit.prevent="submitKomentar" class="mb-16">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <textarea
                                v-model="form.isi_komentar"
                                rows="2"
                                class="w-full rounded-2xl border-gray-100 bg-surface-alt shadow-inner focus:bg-white focus:border-primary focus:ring-primary text-sm font-medium p-5 pr-14 transition-all"
                                placeholder="Tuliskan apresiasi atau pertanyaan Anda..."
                                :disabled="form.processing"
                            ></textarea>
                            <button
                                type="submit"
                                class="absolute top-1/2 -translate-y-1/2 right-4 w-10 h-10 bg-primary text-white rounded-xl shadow-soft flex items-center justify-center hover:bg-primary-dark transition-all disabled:opacity-20"
                                :disabled="form.processing || !form.isi_komentar.trim()"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div v-if="form.errors.isi_komentar" class="text-red-500 text-xs mt-2 font-bold ml-4">{{ form.errors.isi_komentar }}</div>
                </form>

                <div class="space-y-10">
                    <div v-for="komentar in karya.komentars" :key="komentar.id" class="flex gap-4 md:gap-6 group">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-2xl bg-surface-alt border border-gray-100 flex items-center justify-center text-primary font-bold text-lg shadow-soft group-hover:scale-110 transition-transform">
                                {{ komentar.user.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-sm font-bold text-content">{{ komentar.user.name }}</h4>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span class="text-[10px] font-black text-content-light uppercase tracking-widest">
                                    {{ new Date(komentar.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}
                                </span>
                            </div>
                            <div class="p-5 bg-surface-alt rounded-[2rem] rounded-tl-none border border-gray-100 relative">
                                <p class="text-sm text-content-light font-medium leading-relaxed">{{ komentar.isi_komentar }}</p>
                                
                                <!-- Tombol Hapus Komentar (Hanya untuk Admin) -->
                                <button 
                                    v-if="userRole === 'admin'"
                                    @click="() => {
                                        if(confirm('Hapus komentar ini?')) {
                                            $inertia.delete(route('admin.komentar.destroy', komentar.id), { preserveScroll: true });
                                        }
                                    }"
                                    class="absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors"
                                    title="Hapus Komentar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="karya.komentars.length === 0" class="flex flex-col items-center justify-center py-20 bg-surface-alt rounded-[2.5rem] border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-soft flex items-center justify-center mb-4 text-2xl">
                            💭
                        </div>
                        <p class="text-content-light font-bold text-sm tracking-tight">Belum ada diskusi terbuka.</p>
                        <p class="text-xs text-content-light opacity-60 font-medium">Jadilah yang pertama memberikan apresiasi!</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
