// File: resources/js/Pages/Arsip/Create.vue
// Formulir untuk membuat arsip baru, sekarang dengan Dropzone.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Dropzone from '@/Components/Dropzone.vue'; // <-- Import komponen Dropzone

const props = defineProps({
    divisi: Array,
    kategori: Array,
    errors: Object
});

const form = useForm({
    nomor_arsip: '',
    judul: '',
    deskripsi: '',
    divisi_id: '',
    kategori_id: '',
    versi_dokumen: '1.0',
    file: null,
});

const handleFile = (file) => {
    form.file = file;
};

const submit = () => {
    form.post(route('arsip.store'), {
        onFinish: () => form.reset('file'),
    });
};
</script>

<template>
    <Head title="Tambah Arsip Baru" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Arsip Baru</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Nomor Arsip -->
                            <div class="mt-4">
                                <InputLabel for="nomor_arsip" value="Nomor Arsip" />
                                <TextInput id="nomor_arsip" type="text" class="mt-1 block w-full" v-model="form.nomor_arsip" required />
                                <InputError class="mt-2" :message="form.errors.nomor_arsip" />
                            </div>

                            <!-- Judul -->
                            <div class="mt-4">
                                <InputLabel for="judul" value="Judul" />
                                <TextInput id="judul" type="text" class="mt-1 block w-full" v-model="form.judul" required />
                                <InputError class="mt-2" :message="form.errors.judul" />
                            </div>

                            <!-- Deskripsi -->
                            <div class="mt-4">
                                <InputLabel for="deskripsi" value="Deskripsi" />
                                <textarea id="deskripsi" v-model="form.deskripsi" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.deskripsi" />
                            </div>

                            <!-- Divisi -->
                            <div class="mt-4">
                                <InputLabel for="divisi_id" value="Divisi" />
                                <select id="divisi_id" v-model="form.divisi_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="" disabled>Pilih Divisi</option>
                                    <option v-for="d in divisi" :key="d.id" :value="d.id">{{ d.nama_divisi }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.divisi_id" />
                            </div>

                            <!-- Kategori -->
                            <div class="mt-4">
                                <InputLabel for="kategori_id" value="Kategori" />
                                <select id="kategori_id" v-model="form.kategori_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option v-for="k in kategori" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.kategori_id" />
                            </div>

                            <!-- Versi Dokumen -->
                            <div class="mt-4">
                                <InputLabel for="versi_dokumen" value="Versi Dokumen" />
                                <TextInput id="versi_dokumen" type="text" class="mt-1 block w-full" v-model="form.versi_dokumen" required />
                                <InputError class="mt-2" :message="form.errors.versi_dokumen" />
                            </div>

                             <!-- File Upload -->
                            <div class="mt-4">
                                <InputLabel for="file" value="File Dokumen" />
                                <Dropzone @file-dropped="handleFile" class="mt-1" />
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full mt-2">
                                    {{ form.progress.percentage }}%
                                </progress>
                                <InputError class="mt-2" :message="form.errors.file" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Simpan Arsip
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

// ---
