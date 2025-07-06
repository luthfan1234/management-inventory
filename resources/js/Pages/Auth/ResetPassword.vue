// File: resources/js/Pages/Auth/ResetPassword.vue
// Halaman untuk mengatur ulang kata sandi dengan gaya modern.

<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};
const toggleConfirmPassword = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password">
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
                <h2 class="text-xl font-bold mb-1 text-center text-gray-900">Atur Ulang Kata Sandi</h2>
                <p class="text-center text-gray-500 mb-8">
                   Buat kata sandi baru untuk akun Anda.
                </p>

                <form class="space-y-5" @submit.prevent="submit">
                    <div>
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Alamat Email</label>
                        <div class="relative">
                            <input id="email" type="email" v-model="form.email" class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-200 text-sm font-medium text-gray-500 cursor-not-allowed" required readonly />
                            <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                <i class="material-icons text-xl">email</i>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                     <div>
                        <label for="password" class="block text-gray-700 font-semibold mb-2">Kata Sandi Baru</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" v-model="form.password" placeholder="Masukan kata sandi baru" class="w-full pl-10 pr-10 py-3 rounded-xl bg-blue-50 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600" required autocomplete="new-password"/>
                            <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                <i class="material-icons text-xl">lock</i>
                            </div>
                            <div class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500" @click="togglePassword">
                                <i class="material-icons text-lg">{{ showPassword ? 'visibility_off' : 'visibility' }}</i>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation" v-model="form.password_confirmation" placeholder="Ulangi kata sandi baru" class="w-full pl-10 pr-10 py-3 rounded-xl bg-blue-50 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600" required autocomplete="new-password"/>
                            <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                <i class="material-icons text-xl">lock</i>
                            </div>
                            <div class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500" @click="toggleConfirmPassword">
                                <i class="material-icons text-lg">{{ showConfirmPassword ? 'visibility_off' : 'visibility' }}</i>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Simpan Kata Sandi Baru
                    </button>
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
