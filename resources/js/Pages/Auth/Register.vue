<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    kode_peserta: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Daftar Akun" />

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-content">Daftar Akun</h1>
            <p class="text-content-light mt-1">Bergabunglah dalam pameran inovasi</p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nama Lengkap" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-2 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Alamat Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="nama@email.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <InputLabel for="password" value="Kata Sandi" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-2 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Konfirmasi Sandi"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-2 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </div>

            <!-- Input Token Peserta (Opsional) -->
            <div class="mt-6 p-5 bg-blue-50 border border-blue-100 rounded-2xl">
                <InputLabel for="kode_peserta" value="Token Peserta (Opsional)" class="text-primary font-bold" />
                <p class="text-xs text-primary-dark opacity-70 mb-3">Kosongkan jika Anda mendaftar sebagai Pengunjung biasa.</p>
                <TextInput
                    id="kode_peserta"
                    type="text"
                    class="mt-1 block w-full border-blue-100 focus:border-primary focus:ring-primary"
                    v-model="form.kode_peserta"
                    placeholder="Masukkan kode rahasia..."
                />
                <InputError class="mt-2" :message="form.errors.kode_peserta" />
            </div>

            <div class="mt-8">
                <PrimaryButton
                    class="w-full py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Buat Akun Sekarang
                </PrimaryButton>
            </div>

            <div class="mt-8 text-center text-sm text-content-light">
                Sudah punya akun? 
                <Link
                    :href="route('login')"
                    class="font-bold text-primary hover:text-primary-dark underline underline-offset-4"
                >
                    Masuk di sini
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
