<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    kategoris: Array,
});

const form = useForm({
    judul_karya: '',
    kategori_id: '',
    deskripsi_inovasi: '',
    file_multimedia: null,
    link_prototype: '',
});

const previewUrl = ref(null);
const fileType = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    form.file_multimedia = file;

    if (file) {
        fileType.value = file.type.startsWith('video') ? 'video' : 'image';
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
        fileType.value = null;
    }
};

const removeFile = () => {
    form.file_multimedia = null;
    previewUrl.value = null;
    fileType.value = null;
};

const submit = () => {
    form.post(route('peserta.karya.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            removeFile();
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Unggah Karya</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Bagikan inovasi digital Anda kepada para pengunjung.</p>
                </div>
                <Link
                    :href="route('peserta.galeri')"
                    class="text-sm font-bold text-primary hover:text-primary-dark transition-colors whitespace-nowrap flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Galeri
                </Link>
            </div>
        </template>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="bg-white rounded-[2.5rem] shadow-soft-xl border border-gray-100 p-8 md:p-12">
                <form @submit.prevent="submit" class="space-y-8">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <InputLabel for="judul_karya" value="Judul Inovasi" class="ml-1" />
                            <TextInput 
                                id="judul_karya" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.judul_karya" 
                                required 
                                placeholder="Contoh: Aplikasi Smart City PAKID"
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
                                <option value="" disabled>Pilih kategori karya...</option>
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
                            placeholder="Ceritakan latar belakang, solusi, dan fitur utama dari karya Anda..."
                        ></textarea>
                        <InputError :message="form.errors.deskripsi_inovasi" />
                    </div>

                    <div class="space-y-2">
                        <InputLabel for="link_prototype" value="Link Prototype (Opsional)" class="ml-1" />
                        <TextInput 
                            id="link_prototype" 
                            type="url" 
                            class="mt-1 block w-full" 
                            v-model="form.link_prototype" 
                            placeholder="https://figma.com/..."
                        />
                        <InputError :message="form.errors.link_prototype" />
                    </div>

                    <div class="space-y-4">
                        <InputLabel value="Media Presentasi" class="ml-1" />
                        
                        <!-- Area Dropzone / Preview -->
                        <div class="relative group">
                            <input 
                                id="file_multimedia" 
                                type="file" 
                                @change="handleFileUpload" 
                                class="sr-only"
                                :required="!previewUrl"
                                accept="image/*,video/*"
                            />
                            
                            <!-- Preview State -->
                            <div v-if="previewUrl" class="relative w-full h-64 rounded-[2rem] overflow-hidden border-2 border-primary shadow-soft-xl bg-black">
                                <video v-if="fileType === 'video'" :src="previewUrl" class="w-full h-full object-contain" controls></video>
                                <img v-else :src="previewUrl" class="w-full h-full object-contain" />
                                
                                <!-- Overlay Info & Action -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                    <label for="file_multimedia" class="px-6 py-2 bg-white text-content font-bold rounded-xl cursor-pointer hover:bg-surface-alt transition-all">
                                        Ganti File
                                    </label>
                                    <button @click.prevent="removeFile" class="px-6 py-2 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all">
                                        Hapus
                                    </button>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4 py-2 px-4 bg-white/90 backdrop-blur-sm rounded-xl border border-white shadow-soft">
                                    <p class="text-[10px] font-black text-primary uppercase tracking-widest truncate">{{ form.file_multimedia?.name }}</p>
                                </div>
                            </div>

                            <!-- Empty State (No File) -->
                            <label 
                                v-else
                                for="file_multimedia" 
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-200 rounded-[2rem] cursor-pointer bg-surface-alt hover:bg-blue-50 hover:border-primary transition-all group"
                            >
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-12 h-12 bg-white rounded-2xl shadow-soft flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="mb-1 text-sm text-content font-bold">Klik untuk unggah file</p>
                                    <p class="text-xs text-content-light font-medium">PNG, JPG, MP4 (Maks. 100MB)</p>
                                </div>
                            </label>
                        </div>
                        <InputError :message="form.errors.file_multimedia" />
                        
                        <!-- Progress Bar -->
                        <div v-if="form.progress" class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-primary uppercase tracking-widest">Mengunggah...</span>
                                <span class="text-xs font-bold text-primary">{{ form.progress.percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden border border-gray-50">
                                <div 
                                    class="bg-primary h-full transition-all duration-300 ease-out" 
                                    :style="{ width: form.progress.percentage + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-50">
                        <PrimaryButton 
                            class="px-10 py-4 text-base rounded-2xl"
                            :class="{ 'opacity-25': form.processing }" 
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Sedang Menyimpan...' : 'Simpan & Publikasikan' }}
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
