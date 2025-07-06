// File: resources/js/Pages/Arsip/Edit.vue
// Formulir untuk mengedit arsip yang sudah ada, dengan layout dan gaya yang diperbarui.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import Dropzone from '@/Components/Dropzone.vue';

const props = defineProps({
    arsip: Object,
    divisi: Array,
    kategori: Array,
    errors: Object
});

const form = useForm({
    _method: 'PUT', // Method spoofing for file uploads
    nomor_arsip: props.arsip.nomor_arsip,
    judul: props.arsip.judul,
    deskripsi: props.arsip.deskripsi,
    divisi_id: props.arsip.divisi_id,
    kategori_id: props.arsip.kategori_id,
    versi_dokumen: props.arsip.versi_dokumen,
    file: null,
});

const handleFile = (file) => {
    form.file = file;
};

const submit = () => {
    form.post(route('arsip.update', props.arsip.id), {
        onFinish: () => form.reset('file'),
    });
};
</script>

<template>
    <Head title="Edit Arsip" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Edit Arsip</h2>
                <p class="text-sm text-gray-500">Anda sedang mengubah detail untuk dokumen: <span class="font-semibold">{{ arsip.judul }}</span></p>
            </div>
        </template>

        <form @submit.prevent="submit" class="space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 bg-white rounded-2xl p-8 border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Detail Dokumen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-1">
                            <label for="nomor_arsip" class="block text-sm font-medium text-gray-700 mb-1">Nomor Arsip</label>
                            <input id="nomor_arsip" type="text" v-model="form.nomor_arsip" required class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <InputError class="mt-2" :message="form.errors.nomor_arsip" />
                        </div>

                        <div class="md:col-span-1">
                            <label for="versi_dokumen" class="block text-sm font-medium text-gray-700 mb-1">Versi Dokumen</label>
                            <input id="versi_dokumen" type="text" v-model="form.versi_dokumen" required class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <InputError class="mt-2" :message="form.errors.versi_dokumen" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input id="judul" type="text" v-model="form.judul" required class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <InputError class="mt-2" :message="form.errors.judul" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea id="deskripsi" v-model="form.deskripsi" rows="4" class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></textarea>
                            <InputError class="mt-2" :message="form.errors.deskripsi" />
                        </div>

                        <div class="md:col-span-1">
                             <label for="divisi_id" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                             <select id="divisi_id" v-model="form.divisi_id" required class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                 <option v-for="d in divisi" :key="d.id" :value="d.id">{{ d.nama }}</option>
                             </select>
                             <InputError class="mt-2" :message="form.errors.divisi_id" />
                        </div>

                        <div class="md:col-span-1">
                             <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                             <select id="kategori_id" v-model="form.kategori_id" required class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                 <option v-for="k in kategori" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
                             </select>
                             <InputError class="mt-2" :message="form.errors.kategori_id" />
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-2xl p-8 border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Ganti File (Opsional)</h3>
                        <p class="text-sm text-gray-500 mb-3">File saat ini: <span class="font-medium text-gray-700">{{ arsip.file_name }}</span></p>
                        <Dropzone @file-dropped="handleFile" class="mt-1" />
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full mt-4 [&::-webkit-progress-bar]:rounded-lg [&::-webkit-progress-value]:rounded-lg [&::-webkit-progress-bar]:bg-slate-300 [&::-webkit-progress-value]:bg-blue-600 [&::-moz-progress-bar]:bg-blue-600"></progress>
                        <InputError class="mt-2" :message="form.errors.file" />
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-gray-200">
                        <button type="submit"
                                :class="{ 'opacity-50': form.processing }"
                                :disabled="form.processing"
                                class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                            <i class="material-icons mr-2">save</i>
                            Simpan Perubahan
                        </button>
                        <Link :href="route('arsip.index')" class="w-full text-center mt-3 inline-block text-sm text-gray-500 hover:text-gray-800">
                            Batal
                        </Link>
                    </div>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
