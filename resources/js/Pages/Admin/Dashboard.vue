<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    karyas: Object, // Menggunakan Object karena pagination
    total_users: Number,
    total_peserta: Number,
    current_token: String,
});

const formToken = useForm({
    token_baru: props.current_token === 'BELUM-DISET' ? '' : props.current_token,
});

const submitToken = () => {
    formToken.post(route('admin.token.update'), {
        preserveScroll: true,
        onSuccess: () => {
            formToken.token_baru = props.current_token;
        },
    });
};

const deleteKarya = (id) => {
    if (confirm('PERINGATAN ADMIN: Anda yakin ingin menghapus karya ini? Seluruh data terkait (vote, komentar, file) akan dihapus secara permanen.')) {
        router.delete(route('admin.karya.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Admin Control Panel</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Pusat kendali dan moderasi platform PAKID.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a
                        :href="route('admin.laporan.pdf')"
                        target="_blank"
                        class="inline-flex items-center px-6 py-3 bg-white text-content font-bold rounded-2xl shadow-soft border border-gray-100 hover:bg-surface-alt transition-all"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Ekspor Laporan PDF
                    </a>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 space-y-10">

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-[2rem] shadow-soft border border-gray-100 p-8">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-primary mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="text-xs font-black text-content-light uppercase tracking-widest">Pengguna Aktif</p>
                    <p class="text-4xl font-black text-content mt-2">{{ total_users }}</p>
                </div>
                <div class="bg-white rounded-[2rem] shadow-soft border border-gray-100 p-8">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <p class="text-xs font-black text-content-light uppercase tracking-widest">Peserta Inovasi</p>
                    <p class="text-4xl font-black text-content mt-2">{{ total_peserta }}</p>
                </div>
                <div class="bg-white rounded-[2rem] shadow-soft border border-gray-100 p-8">
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <p class="text-xs font-black text-content-light uppercase tracking-widest">Total Karya</p>
                    <p class="text-4xl font-black text-content mt-2">{{ karyas.total }}</p>
                </div>
            </div>

            <!-- Configuration Panel -->
            <div class="bg-primary rounded-[2.5rem] shadow-soft-xl border border-primary-dark overflow-hidden p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="max-w-xl text-center md:text-left">
                    <h3 class="text-2xl font-bold text-white tracking-tight">Token Registrasi Peserta</h3>
                    <p class="text-blue-100 font-medium text-sm mt-2 leading-relaxed">
                        Kode rahasia untuk mendaftar sebagai peserta. Jika dirasa sudah tidak aman, segera perbarui token Anda di sini.
                    </p>
                </div>

                <form @submit.prevent="submitToken" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <div class="relative flex-grow">
                        <input
                            v-model="formToken.token_baru"
                            type="text"
                            class="w-full sm:w-64 rounded-2xl bg-primary-dark/30 border-primary-dark/20 text-white placeholder-blue-300 focus:border-white focus:ring-white text-sm font-black px-6 py-4 transition-all"
                            placeholder="Token baru..."
                        >
                        <div v-if="formToken.errors.token_baru" class="absolute -bottom-6 left-2 text-red-200 text-[10px] font-black uppercase tracking-widest">{{ formToken.errors.token_baru }}</div>
                    </div>
                    <button type="submit" :disabled="formToken.processing" class="px-8 py-4 bg-white text-primary font-black text-sm rounded-2xl shadow-soft hover:bg-surface-alt transition-all disabled:opacity-50 whitespace-nowrap">
                        Update Token
                    </button>
                </form>
            </div>

            <!-- Moderation Table -->
            <div class="bg-white rounded-[3rem] shadow-soft-xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-content tracking-tight">Moderasi Karya</h3>
                    <span class="text-[10px] font-black text-content-light uppercase tracking-widest">Antrean Publikasi</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] font-black text-content-light uppercase tracking-widest bg-surface-alt border-b border-gray-50">    
                            <tr>
                                <th scope="col" class="px-8 py-5">Karya & Kategori</th>
                                <th scope="col" class="px-8 py-5">Inovator</th>
                                <th scope="col" class="px-8 py-5 text-center">Statistik</th>
                                <th scope="col" class="px-8 py-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="karya in karyas.data" :key="karya.id" class="group hover:bg-surface-alt transition-colors">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-content leading-snug group-hover:text-primary transition-colors">{{ karya.judul_karya }}</div>
                                    <div class="text-[10px] font-black text-primary uppercase tracking-widest mt-1.5">{{ karya.kategori.nama_kategori }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-content">{{ karya.user.name }}</div>
                                    <div class="text-[10px] font-black text-content-light uppercase tracking-widest mt-1">{{ new Date(karya.created_at).toLocaleDateString('id-ID') }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-4 text-xs font-bold text-content-light">
                                        <span class="flex items-center gap-1.5">❤️ {{ karya.votes_count }}</span>
                                        <span class="flex items-center gap-1.5">💬 {{ karya.komentars_count }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Link
                                            :href="route('karya.show', karya.id)"
                                            class="p-2 bg-white text-content border border-gray-100 rounded-xl hover:bg-primary hover:text-white transition-all shadow-soft"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>      
                                        </Link>
                                        <button
                                            @click="deleteKarya(karya.id)"
                                            class="p-2 bg-white text-red-500 border border-gray-100 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-soft"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="karyas.data.length === 0">
                                <td colspan="4" class="px-8 py-20 text-center text-content-light font-bold">Belum ada karya yang masuk dalam antrean.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="karyas.links.length > 3" class="px-8 py-6 bg-surface-alt border-t border-gray-50 flex items-center justify-center gap-2">
                    <template v-for="(link, k) in karyas.links" :key="k">
                        <div 
                            v-if="link.url === null" 
                            class="px-4 py-2 text-xs font-black text-content-light opacity-50 uppercase tracking-widest cursor-not-allowed"
                            v-html="link.label"
                        ></div>
                        <Link 
                            v-else 
                            :href="link.url" 
                            class="px-4 py-2 text-xs font-black uppercase tracking-widest transition-all rounded-xl"
                            :class="link.active ? 'bg-primary text-white shadow-soft' : 'text-content hover:bg-white hover:shadow-soft border border-transparent hover:border-gray-100'"
                            v-html="link.label"
                        ></Link>
                    </template>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
