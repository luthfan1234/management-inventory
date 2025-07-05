// File: resources/js/Pages/Arsip/Show.vue
// Halaman untuk menampilkan detail satu arsip, sekarang dengan tombol Preview.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PdfPreviewModal from '@/Components/PdfPreviewModal.vue';

const props = defineProps({
    arsip: Object
});

const showPdfModal = ref(false);
const pdfPreviewUrl = ref('');

const openPdfPreview = () => {
    pdfPreviewUrl.value = route('arsip.preview', props.arsip.id);
    showPdfModal.value = true;
};

const closePdfPreview = () => {
    showPdfModal.value = false;
    pdfPreviewUrl.value = '';
};

const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 Bytes';
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Arsip Dokumen</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-2xl font-bold mb-2">{{ arsip.judul }}</h3>
                                <p class="text-sm text-gray-500 mb-4">Nomor Arsip: {{ arsip.nomor_arsip }}</p>

                                <div class="prose max-w-none mb-6">
                                    <p>{{ arsip.deskripsi || 'Tidak ada deskripsi.' }}</p>
                                </div>

                                <!-- Tombol Aksi Utama -->
                                <div class="flex items-center space-x-3">
                                    <Link :href="route('arsip.download', arsip.id)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span>Unduh</span>
                                    </Link>
                                    <button v-if="arsip.file_type === 'pdf'" @click="openPdfPreview" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>Preview</span>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <div class="bg-gray-50 p-4 rounded-lg border">
                                    <h4 class="font-bold mb-3">Detail Dokumen</h4>
                                    <dl>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Divisi:</dt>
                                            <dd class="font-medium">{{ arsip.divisi.nama_divisi }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Kategori:</dt>
                                            <dd class="font-medium">{{ arsip.kategori.nama_kategori }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Versi:</dt>
                                            <dd class="font-medium">{{ arsip.versi_dokumen }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Diunggah oleh:</dt>
                                            <dd class="font-medium">{{ arsip.uploader.name }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Tanggal Unggah:</dt>
                                            <dd class="font-medium text-right">{{ formatDate(arsip.created_at) }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Nama File:</dt>
                                            <dd class="font-medium text-right truncate" :title="arsip.file_name">{{ arsip.file_name }}</dd>
                                        </div>
                                         <div class="py-2 flex justify-between">
                                            <dt class="text-gray-600">Ukuran File:</dt>
                                            <dd class="font-medium">{{ formatBytes(arsip.file_size) }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 border-t pt-6 flex justify-start items-center">
                             <Link :href="route('arsip.index')" class="text-blue-600 hover:text-blue-800">
                                &larr; Kembali ke Daftar Arsip
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <PdfPreviewModal :show="showPdfModal" :pdf-url="pdfPreviewUrl" :title="arsip.judul" @close="closePdfPreview" />

    </AuthenticatedLayout>
</template>

// ---
