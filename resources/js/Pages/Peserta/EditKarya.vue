<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    karya: Object,
    kategoris: Array,
});

const form = useForm({
    judul_karya: props.karya.judul_karya,
    kategori_id: props.karya.kategori_id,
    deskripsi_inovasi: props.karya.deskripsi_inovasi,
    file_multimedia: null,
    link_prototype: props.karya.link_prototype,
});

const handleFileUpload = (event) => {
    form.file_multimedia = event.target.files[0];
};

const submitUpdate = () => {
    // Pakai post karena kita kirim file (Laravel butuh POST untuk multipart/form-data)
    form.post(route('karya.update', props.karya.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Inovasi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Edit Inovasi</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Perbarui detail karya Anda agar tetap relevan.</p>
                </div>
                <Link
                    :href="route('peserta.galeri')"
                    class="text-sm font-bold text-primary hover:text-primary-dark transition-colors whitespace-nowrap flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal & Kembali
                </Link>
            </div>
        </template>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="bg-white rounded-[2.5rem] shadow-soft-xl border border-gray-100 p-8 md:p-12">
                <form @submit.prevent="submitUpdate" class="space-y-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <InputLabel for="judul_karya" value="Judul Inovasi" class="ml-1" />
                            <TextInput 
                                id="judul_karya" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.judul_karya" 
                                required 
                            />
                            <InputError :message="form.errors.judul_karya" />
                        </div>

                        <div class="space-y-2">
                            <InputLabel for="kategori_id" value="Kategori" class="ml-1" />
                            <select 
                                id="kategori_id" 
                                v-model="form.kategori_id" 
                                class="mt-1 block w-full border-gray-200 focus:border-primary focus:ring-primary rounded-xl shadow-sm transition-all" 
                                required
                            >
                                <option v-for="kategori in kategoris" :key="kategori.id" :value="kategori.id">
                                    {{ kategori.nama_kategori }}
                                </option>
                            </select>
                            <InputError :message="form.errors.kategori_id" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <InputLabel for="deskripsi_inovasi" value="Deskripsi Inovasi" class="ml-1" />
                        <textarea 
                            id="deskripsi_inovasi" 
                            v-model="form.deskripsi_inovasi" 
                            class="mt-1 block w-full border-gray-200 focus:border-primary focus:ring-primary rounded-2xl shadow-sm transition-all min-h-[150px]" 
                            rows="5" 
                            required 
                        ></textarea>
                        <InputError :message="form.errors.deskripsi_inovasi" />
                    </div>

                    <div class="space-y-2">
                        <InputLabel for="link_prototype" value="Link Prototype" class="ml-1" />
                        <TextInput 
                            id="link_prototype" 
                            type="url" 
                            class="mt-1 block w-full" 
                            v-model="form.link_prototype" 
                        />
                        <InputError :message="form.errors.link_prototype" />
                    </div>

                    <div class="p-8 bg-surface-alt rounded-[2rem] border border-gray-100 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-soft flex items-center justify-center text-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-content">Ganti Media Presentasi</h4>
                                <p class="text-xs text-content-light font-medium mt-0.5">Kosongkan jika tidak ingin mengubah file saat ini.</p>
                            </div>
                        </div>

                        <div class="relative group mt-4">
                            <input 
                                id="file_multimedia" 
                                type="file" 
                                @change="handleFileUpload" 
                                class="sr-only"
                                accept="image/*,video/*"
                            />
                            <label 
                                for="file_multimedia" 
                                class="flex items-center justify-between w-full p-4 bg-white border border-gray-100 rounded-xl cursor-pointer hover:border-primary transition-all"
                            >
                                <span class="text-xs font-bold text-content-light truncate pr-4">
                                    {{ form.file_multimedia ? form.file_multimedia.name : 'Pilih file baru...' }}
                                </span>
                                <span class="px-4 py-1.5 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-soft">
                                    Browse
                                </span>
                            </label>
                        </div>
                        <InputError :message="form.errors.file_multimedia" />

                        <!-- Progress Bar (Internal) -->
                        <div v-if="form.progress" class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black text-primary uppercase tracking-widest">Memproses...</span>
                                <span class="text-[10px] font-black text-primary">{{ form.progress.percentage }}%</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-2 overflow-hidden border border-gray-50">
                                <div class="bg-primary h-full transition-all duration-300" :style="{ width: form.progress.percentage + '%' }"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-50 gap-4">
                        <Link :href="route('peserta.galeri')" class="px-8 py-4 text-sm font-bold text-content-light hover:text-content transition-colors">
                            Batal
                        </Link>
                        <PrimaryButton 
                            class="px-10 py-4 text-base rounded-2xl"
                            :class="{ 'opacity-25': form.processing }" 
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>