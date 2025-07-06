// File: resources/js/Pages/Arsip/Show.vue
// Halaman untuk menampilkan detail arsip, dengan PDF yang langsung di-embed di halaman.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue'; // Mengganti ref dengan computed

const props = defineProps({
    arsip: Object,
    auth: Object
});

// URL untuk PDF embed dibuat secara computed, lebih ringkas.
const pdfEmbedUrl = computed(() => {
    // Pastikan hanya membuat URL jika filenya PDF
    return props.arsip.file_type === 'pdf' ? route('arsip.preview', props.arsip.id) : null;
});

// Fungsi formatBytes dan formatDate tidak berubah
const formatBytes = (bytes, decimals = 2) => {
    if (!bytes || bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};
</script>

<template>
    <Head :title="'Detail Arsip: ' + arsip.judul" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Detail Dokumen</h2>
                    <p class="text-sm text-gray-500">Nomor Arsip: {{ arsip.nomor_arsip }}</p>
                </div>
                </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-2xl p-8 border border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ arsip.judul }}</h1>
                <div class="prose prose-lg max-w-none text-gray-700 mb-8">
                    <p>{{ arsip.deskripsi || 'Tidak ada deskripsi untuk dokumen ini.' }}</p>
                </div>

                <div v-if="pdfEmbedUrl" class="border-t border-gray-200 pt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Preview Dokumen</h3>
                    <iframe
                        :src="pdfEmbedUrl"
                        class="w-full h-[800px] rounded-lg border border-gray-200 bg-gray-50"
                        title="PDF Preview"
                    ></iframe>
                </div>
                 </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 border border-gray-200 sticky top-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Informasi Dokumen</h3>
                    <ul class="divide-y divide-gray-200">
                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">business</i>
                            <div>
                                <p class="text-sm text-gray-500">Divisi</p>
                                <p class="font-semibold text-gray-800">{{ arsip.divisi.nama }}</p>
                            </div>
                        </li>

                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">category</i>
                            <div>
                                <p class="text-sm text-gray-500">Kategori</p>
                                <p class="font-semibold text-gray-800">{{ arsip.kategori.nama_kategori }}</p>
                            </div>
                        </li>

                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">tag</i>
                            <div>
                                <p class="text-sm text-gray-500">Versi</p>
                                <p class="font-semibold text-gray-800">{{ arsip.versi_dokumen }}</p>
                            </div>
                        </li>

                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">person</i>
                            <div>
                                <p class="text-sm text-gray-500">Diunggah oleh</p>
                                <p class="font-semibold text-gray-800">{{ arsip.uploader.name }}</p>
                            </div>
                        </li>

                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">calendar_today</i>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Unggah</p>
                                <p class="font-semibold text-gray-800">{{ formatDate(arsip.created_at) }}</p>
                            </div>
                        </li>

                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">attachment</i>
                            <div>
                                <p class="text-sm text-gray-500">Nama File</p>
                                <p class="font-semibold text-gray-800 break-all">{{ arsip.file_name }}</p>
                            </div>
                        </li>

                        <li class="flex items-start py-4">
                            <i class="material-icons text-lg text-gray-400 mt-1 mr-4">storage</i>
                            <div>
                                <p class="text-sm text-gray-500">Ukuran File</p>
                                <p class="font-semibold text-gray-800">{{ formatBytes(arsip.file_size) }}</p>
                            </div>
                        </li>

                        <li class="pt-6">
                             <div class="flex flex-col space-y-3">
                                <Link :href="route('arsip.download', arsip.id)" class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                                    <i class="material-icons text-base mr-2">download</i>
                                    <span>Unduh Dokumen</span>
                                </Link>
                                <Link v-if="auth.user.role === 'admin'" :href="route('arsip.edit', arsip.id)" class="w-full inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors">
                                    <i class="material-icons text-base mr-2">edit</i>
                                    <span>Edit Informasi</span>
                                </Link>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        </AuthenticatedLayout>
</template>
