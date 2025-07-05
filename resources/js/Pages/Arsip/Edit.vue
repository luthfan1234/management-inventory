// File: resources/js/Pages/Arsip/Edit.vue
// Formulir untuk mengedit arsip yang sudah ada, sekarang dengan Dropzone.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Dropzone from '@/Components/Dropzone.vue'; // <-- Import komponen Dropzone

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
    // Inertia handles multipart form data with PUT/PATCH by converting it to a POST request
    // with a _method field. This is why we use form.post here.
    form.post(route('arsip.update', props.arsip.id), {
        onFinish: () => form.reset('file'),
    });
};
</script>

<template>
    <Head title="Edit Arsip" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Arsip: {{ arsip.judul }}</h2>
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
                                    <option v-for="d in divisi" :key="d.id" :value="d.id">{{ d.nama_divisi }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.divisi_id" />
                            </div>

                            <!-- Kategori -->
                            <div class="mt-4">
                                <InputLabel for="kategori_id" value="Kategori" />
                                <select id="kategori_id" v-model="form.kategori_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
                                <InputLabel for="file" value="Ganti File Dokumen (Opsional)" />
                                 <p class="text-sm text-gray-500 mb-2">File saat ini: {{ arsip.file_name }}</p>
                                <Dropzone @file-dropped="handleFile" class="mt-1" />
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full mt-2">
                                    {{ form.progress.percentage }}%
                                </progress>
                                <InputError class="mt-2" :message="form.errors.file" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <Link :href="route('arsip.index')" class="text-gray-600 hover:text-gray-900 mr-4">Batal</Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Perbarui Arsip
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
