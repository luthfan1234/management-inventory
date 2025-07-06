// File: resources/js/Pages/Auth/Login.vue
<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};


const form = useForm({
    email: '',
    password: '',
    remember: false,
    role: 'admin',
});

const isAdmin = ref(true);
const updateRole = (admin) => {
    isAdmin.value = admin;
    form.role = admin ? 'admin' : 'user';
};
const emailPlaceholder = computed(() => isAdmin.value ? 'masukan email admin' : 'masukan email user');
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            if (errors.role) console.error('Role mismatch:', errors.role);
        },
    });
};
</script>

<template>
    <Head title="Log in">
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
                <h2 class="text-xl font-bold mb-1 text-center text-gray-900">Arsip Digital DAOP 6 Yogyakarta</h2>
                <p class="text-center text-gray-500 mb-6"> Selamat datang🙌🏻, silakan login untuk mulai mengelola arsip Anda.</p>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
                    {{ status }}
                </div>

                <div class="flex space-x-2 bg-gray-100 p-1 rounded-xl mb-8">
                    <button type="button" :class="{ 'bg-white shadow-sm text-[#0065B3]': isAdmin, 'text-gray-500 hover:bg-white': !isAdmin }" @click="updateRole(true)" class="flex items-center justify-center py-2 px-16 rounded-xl transition font-semibold">
                        <i class="material-icons text-lg mr-2">admin_panel_settings</i>
                        <span>Admin</span>
                    </button>
                    <button type="button" :class="{ 'bg-white shadow-sm text-[#0065B3]': !isAdmin, 'text-gray-500 hover:bg-white': isAdmin }" @click="updateRole(false)" class="flex items-center justify-center py-2 px-16 rounded-xl transition font-semibold">
                        <i class="material-icons text-lg mr-2">person</i>
                        <span>User</span>
                    </button>
                </div>
                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                        <div class="relative">
                            <input id="email" v-model="form.email" type="email" :placeholder="emailPlaceholder" class="w-full pl-10 pr-4 py-3 rounded-xl bg-blue-50 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0065B3]" required autofocus autocomplete="username" />
                            <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                <i class="material-icons text-xl">email</i>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    v-model="form.password"
                                    placeholder="password"
                                    class="w-full pl-10 pr-10 py-3 rounded-xl bg-blue-50 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0065B3]"
                                    required
                                    autocomplete="current-password"/>
                            <div class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                <i class="material-icons text-xl">lock</i>
                            </div>
                            <div class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500" @click="togglePassword">
                                <i class="material-icons text-lg">{{ showPassword ? 'visibility_off' : 'visibility' }}</i>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <InputError class="mt-2" :message="form.errors.role" />
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-[#0065B3] hover:underline">Lupa password?</Link>
                    </div>
                    <button type="submit" class="w-full bg-[#0065B3] text-white rounded-xl py-3 font-semibold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F7941E] transition" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Sedang Login...' : `Masuk sebagai ${isAdmin ? 'Admin' : 'User'}` }}
                    </button>
                </form>
            </section>
            <section class="hidden md:flex flex-col justify-between rounded-3xl bg-gradient-to-b from-[#0065B3] to-blue-900 text-white p-10 shadow-lg h-full">
                <div>
                    <p class="text-sm mb-3 text-blue-200 font-semibold">— PT Kereta Api Indonesia (Persero)</p>
                    <h2 class="font-extrabold text-3xl sm:text-4xl leading-snug">Mengelola dan Mengamankan Aset Digital<br />DAOP 6 Yogyakarta</h2>
                </div>

                <div class="mt-auto text-center">
                    <p class="text-sm font-medium opacity-80">© 2025 Divisi Regional VI Yogyakarta</p>
                </div>
            </section>
        </div>
    </div>
</template>
                   