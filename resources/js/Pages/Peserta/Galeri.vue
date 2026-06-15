<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    karyas: Array,
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Galeri Karya Saya</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <div v-for="karya in karyas" :key="karya.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <img 
                            :src="'/storage/' + karya.file_multimedia" 
                            alt="Multimedia Karya" 
                            class="w-full h-48 object-cover"
                        >
                        
                        <div class="p-6">
                            <span class="text-xs font-bold text-indigo-600 uppercase">{{ karya.kategori.nama_kategori }}</span>
                            <h3 class="text-lg font-bold text-gray-900 mt-1">{{ karya.judul_karya }}</h3>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-3">{{ karya.deskripsi_inovasi }}</p>
                        </div>
                    </div>

                    <div v-if="karyas.length === 0" class="col-span-full text-center py-10 text-gray-500">
                        Belum ada karya yang diunggah.
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>