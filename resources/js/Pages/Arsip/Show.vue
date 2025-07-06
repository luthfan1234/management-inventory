// File: resources/js/Pages/Arsip/Show.vue
// Halaman untuk menampilkan detail satu arsip, dengan gaya modern yang baru.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PdfPreviewModal from '@/Components/PdfPreviewModal.vue';

const props = defineProps({
    arsip: Object,
    auth: Object // Pastikan mengirimkan 'auth' dari controller
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
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <button v-if="arsip.file_type === 'pdf'" @click="openPdfPreview" class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors">
                        <i class="material-icons text-base mr-2">visibility</i>
                        <span>Preview</span>
                    </button>

                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-2xl p-8 border border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ arsip.judul }}</h1>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p>{{ arsip.deskripsi || 'Tidak ada deskripsi untuk dokumen ini.' }}</p>
                </div>
            </div>

            <div class="lg:col-span-1 bg-white rounded-2xl p-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Informasi Dokumen</h3>
                <ul class="divide-y divide-gray-200">
                    <li class="flex items-start py-4">
                        <i class="material-icons text-lg text-gray-400 mt-1 mr-4">business</i>
                        <div>
                            <p class="text-sm text-gray-500">Divisi</p>
                            <p class="font-semibold text-gray-800">{{ arsip.divisi.nama_divisi }}</p>
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

                    <li class="flex items-start py-4 gap-2">
                        <Link :href="route('arsip.download', arsip.id)" class="inline-flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-700 font-semibold py-2 px-4 rounded-lg transition-colors">
                            <i class="material-icons text-base mr-2">download</i>
                            <span>Unduh</span>
                        </Link>
                        <Link v-if="auth.user.role === 'admin'" :href="route('arsip.edit', arsip.id)" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            <i class="material-icons text-base mr-2">edit</i>
                            <span>Edit</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>

        <PdfPreviewModal :show="showPdfModal" :pdf-url="pdfPreviewUrl" :title="arsip.judul" @close="closePdfPreview" />

    </AuthenticatedLayout>
</template>
