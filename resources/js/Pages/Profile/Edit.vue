<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profil Saya" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-content tracking-tight">Profil Saya</h2>
                    <p class="text-content-light mt-1 font-medium text-sm">Kelola informasi akun dan pengaturan keamanan Anda.</p>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 space-y-8">
            <div class="bg-white p-8 md:p-12 shadow-soft-xl rounded-[2.5rem] border border-gray-100">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div class="bg-white p-8 md:p-12 shadow-soft-xl rounded-[2.5rem] border border-gray-100">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="bg-white p-8 md:p-12 shadow-soft-xl rounded-[2.5rem] border border-red-50">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
