<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    topKaryas: Array,
});

const getRankColor = (index) => {
    if (index === 0) return 'text-yellow-500';
    if (index === 1) return 'text-slate-400';
    if (index === 2) return 'text-amber-700';
    return 'text-content-light';
};

const getRankBg = (index) => {
    if (index === 0) return 'bg-yellow-50 border-yellow-100';
    if (index === 1) return 'bg-slate-50 border-slate-100';
    if (index === 2) return 'bg-amber-50 border-amber-100';
    return 'bg-surface-alt border-gray-100';
};
</script>

<template>
    <Head title="Leaderboard Inovasi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Leaderboard</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Karya-karya terbaik pilihan pengunjung pameran.</p>
                </div>
                <div class="hidden md:block">
                    <span class="px-4 py-2 bg-blue-50 text-primary text-xs font-bold rounded-full border border-blue-100 uppercase tracking-widest">
                        Real-time Ranking
                    </span>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <!-- Podium (Top 3) -->
            <div v-if="topKaryas.length >= 3" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 items-end">
                <!-- 2nd Place -->
                <div class="order-2 md:order-1 flex flex-col items-center">
                    <div class="relative mb-4 group">
                        <div class="w-32 h-32 rounded-3xl overflow-hidden border-4 border-slate-200 shadow-soft-xl group-hover:scale-105 transition-transform">
                            <img :src="'/storage/' + topKaryas[1].file_multimedia" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-slate-400 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-lg border-2 border-white">2</div>
                    </div>
                    <h3 class="font-bold text-content text-lg text-center truncate w-full px-4">{{ topKaryas[1].judul_karya }}</h3>
                    <p class="text-sm text-content-light font-medium">{{ topKaryas[1].user.name }}</p>
                    <div class="mt-2 px-4 py-1 bg-white rounded-full border border-gray-100 shadow-soft text-sm font-bold text-primary">
                        ❤️ {{ topKaryas[1].votes_count }} Votes
                    </div>
                </div>

                <!-- 1st Place -->
                <div class="order-1 md:order-2 flex flex-col items-center">
                    <div class="relative mb-6 group scale-110 md:scale-125">
                        <div class="w-32 h-32 rounded-[2rem] overflow-hidden border-4 border-yellow-400 shadow-soft-xl group-hover:scale-105 transition-transform ring-8 ring-yellow-400/10">
                            <img :src="'/storage/' + topKaryas[0].file_multimedia" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 w-12 h-12 rounded-2xl flex items-center justify-center font-black text-xl shadow-lg border-2 border-white">1</div>
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 text-3xl animate-bounce">👑</div>
                    </div>
                    <h3 class="font-bold text-content text-xl text-center truncate w-full px-4 mt-8">{{ topKaryas[0].judul_karya }}</h3>
                    <p class="text-sm text-content-light font-medium">{{ topKaryas[0].user.name }}</p>
                    <div class="mt-3 px-6 py-1.5 bg-primary text-white rounded-full shadow-soft-xl text-sm font-bold">
                        ❤️ {{ topKaryas[0].votes_count }} Votes
                    </div>
                </div>

                <!-- 3rd Place -->
                <div class="order-3 md:order-3 flex flex-col items-center">
                    <div class="relative mb-4 group">
                        <div class="w-32 h-32 rounded-3xl overflow-hidden border-4 border-amber-600/30 shadow-soft-xl group-hover:scale-105 transition-transform">
                            <img :src="'/storage/' + topKaryas[2].file_multimedia" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-amber-700 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold shadow-lg border-2 border-white">3</div>
                    </div>
                    <h3 class="font-bold text-content text-lg text-center truncate w-full px-4">{{ topKaryas[2].judul_karya }}</h3>
                    <p class="text-sm text-content-light font-medium">{{ topKaryas[2].user.name }}</p>
                    <div class="mt-2 px-4 py-1 bg-white rounded-full border border-gray-100 shadow-soft text-sm font-bold text-primary">
                        ❤️ {{ topKaryas[2].votes_count }} Votes
                    </div>
                </div>
            </div>

            <!-- List of All Rank -->
            <div class="bg-white rounded-3xl shadow-soft-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-content">Peringkat Lengkap</h3>
                    <span class="text-xs font-bold text-content-light uppercase tracking-widest">Top 10 Karya</span>
                </div>
                
                <div class="divide-y divide-gray-50">
                    <div v-for="(karya, index) in topKaryas" :key="karya.id" 
                        class="group flex items-center p-4 sm:p-6 hover:bg-surface-alt transition-colors"
                    >
                        <!-- Rank Number -->
                        <div class="w-12 flex-shrink-0 font-black text-2xl tracking-tighter italic" :class="getRankColor(index)">
                            #{{ index + 1 }}
                        </div>

                        <!-- Info -->
                        <div class="flex flex-1 items-center gap-4 min-w-0">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden border border-gray-100 flex-shrink-0">
                                <img :src="'/storage/' + karya.file_multimedia" class="w-full h-full object-cover" />
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">{{ karya.kategori?.nama_kategori }}</div>
                                <h4 class="font-bold text-content truncate max-w-xs md:max-w-md">{{ karya.judul_karya }}</h4>
                                <p class="text-xs text-content-light font-medium mt-0.5">Oleh {{ karya.user?.name }}</p>
                            </div>
                        </div>

                        <!-- Stats & Action -->
                        <div class="flex items-center gap-6">
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="text-lg font-bold text-content tracking-tighter">{{ karya.votes_count }}</span>
                                <span class="text-[10px] font-bold text-content-light uppercase tracking-widest">Votes</span>
                            </div>
                            <Link :href="route('karya.show', karya.id)" class="p-3 bg-surface-alt text-content hover:bg-primary hover:text-white rounded-2xl transition-all group-hover:translate-x-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </Link>
                        </div>
                    </div>
                    
                    <div v-if="topKaryas.length === 0" class="py-20 text-center text-content-light font-medium">
                        Belum ada karya yang masuk dalam peringkat.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
