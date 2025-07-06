// File: resources/js/Pages/Auth/ConfirmPassword.vue
// Halaman konfirmasi password disesuaikan dengan layout halaman Login.

<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Konfirmasi Kata Sandi">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </Head>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-6 md:px-10">
        <div class="container max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-[2fr_3fr] gap-8 md:gap-20 items-center">

            <section class="bg-white rounded-3xl p-8 sm:p-12 w-full max-w-xl mx-auto shadow-xl">
                <div class="flex flex-col items-center mb-6">
                    <div class="w-24 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 294.74 124.22" class="w-full h-auto">
                            <title>Logo KAI Baru</title>
                            <g id="Layer_2">
                                <g id="Layer_1-2">
                                    <path fill="#2d2a70" d="M99.58,124.22h28.56l-6.55-10.77Zm16.67-19.53L86.56,55.91,144.12,0H98.65a13.65,13.65,0,0,0-9.54,3.88L48.79,43.28,53.33,0H12.27L0,116.81a6.71,6.71,0,0,0,6.68,7.42h33.6L43.07,98,55.45,86l21.78,34.43a8.13,8.13,0,0,0,6.87,3.78H99.58l7.81-15.57Z"/>
                                    <path fill="#ed6b23" d="M141,124.22l55.61-33.81,7.08,28.71a6.71,6.71,0,0,0,6.52,5.11h36L230.13,70l61.24-37.24.26-2.5-192,93.95Zm83.38-73.65L209.37,0H174a19.52,19.52,0,0,0-17.45,10.77L106,111.37,292,26.52l.29-2.85ZM164.6,74.24,177,48l3.27-7.25a2.23,2.23,0,0,1,4.19.38l5.67,23Z"/>
                                    <path fill="#2d2a70" d="M269.53,0a19.52,19.52,0,0,0-19.41,17.49l-2.5,23.88,44.69-17.7L294.74,0Zm-30.6,124.22h43l9.42-91.45L245.6,60.61Z"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="material-icons text-3xl text-yellow-500">lock</i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Area Aman</h2>
                    <p class="text-sm text-gray-500 mt-2">
                        Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
                    </p>
                </div>

                <form @submit.prevent="submit">
                     <div class="space-y-6">
                        <div>
                            <label for="password" class="block text-gray-700 font-semibold mb-2">Kata Sandi</label>
                            <div class="relative">
                                <input id="password" type="password" v-model="form.password" placeholder="Masukan kata sandi Anda" class="w-full pl-10 pr-4 py-3 rounded-xl bg-blue-50 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600" required autocomplete="current-password" autofocus />
                                <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                    <i class="material-icons text-xl">lock</i>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition" :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                            Konfirmasi Kata Sandi
                        </button>
                    </div>
                </form>
            </section>

            <section class="hidden md:flex flex-col justify-between rounded-3xl bg-gradient-to-b from-blue-600 to-blue-800 text-white p-10 shadow-lg h-full">
                <div>
                    <p class="text-sm mb-3 text-blue-200 font-semibold">— PT Kereta Api Indonesia (Persero)</p>
                    <h2 class="font-extrabold text-3xl sm:text-4xl leading-snug">Mengelola dan Mengamankan Aset Digital<br />DAOP 6 Yogyakarta</h2>
                </div>
                <div class="mt-auto text-center">
                    <p class="text-sm font-medium opacity-80">© {{ new Date().getFullYear() }} Divisi Regional VI Yogyakarta</p>
                </div>
            </section>
        </div>
    </div>
</template>
