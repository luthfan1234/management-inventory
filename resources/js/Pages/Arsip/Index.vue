// File: resources/js/Pages/Arsip/Index.vue
// Halaman utama untuk menampilkan, mencari, dan memfilter daftar arsip.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash';
import PdfPreviewModal from '@/Components/PdfPreviewModal.vue';

const props = defineProps({
    arsip: Object,
    divisi: Array,
    kategori: Array,
    filters: Object,
    flash: Object,
    auth: Object
});

const search = ref(props.filters.search);
const divisiId = ref(props.filters.divisi_id);
const kategoriId = ref(props.filters.kategori_id);

const showPdfModal = ref(false);
const pdfPreviewUrl = ref('');
const pdfPreviewTitle = ref('');

const openPdfPreview = (item) => {
    pdfPreviewUrl.value = route('arsip.preview', item.id);
    pdfPreviewTitle.value = item.judul;
    showPdfModal.value = true;
};

const closePdfPreview = () => {
    showPdfModal.value = false;
    pdfPreviewUrl.value = '';
    pdfPreviewTitle.value = '';
};

watch([search, divisiId, kategoriId], debounce(() => {
    router.get(route('arsip.index'), {
        search: search.value,
        divisi_id: divisiId.value,
        kategori_id: kategoriId.value
    }, {
        preserveState: true,
        replace: true
    });
}, 300));

const exportUrl = computed(() => {
    const params = new URLSearchParams({
        search: search.value || '',
        divisi_id: divisiId.value || '',
        kategori_id: kategoriId.value || ''
    }).toString();
    return `${route('arsip.export')}?${params}`;
});


const deleteArsip = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus arsip ini?')) {
        router.delete(route('arsip.destroy', id), {
            preserveScroll: true
        });
    }
};

</script>

<template>
    <Head title="Daftar Arsip" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Arsip Dokumen</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                            {{ $page.props.flash.success }}
                        </div>
                        <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                            {{ $page.props.flash.error }}
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <Link v-if="auth.user.role === 'admin'" :href="route('arsip.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Arsip
                            </Link>
                             <a :href="exportUrl" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Export Excel</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <input type="text" v-model="search" placeholder="Cari judul atau nomor arsip..." class="form-input rounded-md shadow-sm">
                            <select v-model="divisiId" class="form-select rounded-md shadow-sm">
                                <option :value="null">Semua Divisi</option>
                                <option v-for="d in divisi" :key="d.id" :value="d.id">{{ d.nama_divisi }}</option>
                            </select>
                            <select v-model="kategoriId" class="form-select rounded-md shadow-sm">
                                <option :value="null">Semua Kategori</option>
                                <option v-for="k in kategori" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
                            </select>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="py-3 px-6 text-left">Nomor Arsip</th>
                                        <th class="py-3 px-6 text-left">Judul</th>
                                        <th class="py-3 px-6 text-left">Divisi</th>
                                        <th class="py-3 px-6 text-left">Kategori</th>
                                        <th class="py-3 px-6 text-left">Tipe File</th>
                                        <th class="py-3 px-6 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <tr v-if="arsip.data.length === 0">
                                        <td colspan="6" class="text-center py-4">Tidak ada data arsip.</td>
                                    </tr>
                                    <tr v-for="item in arsip.data" :key="item.id" class="border-b">
                                        <td class="py-3 px-6">{{ item.nomor_arsip }}</td>
                                        <td class="py-3 px-6">{{ item.judul }}</td>
                                        <td class="py-3 px-6">{{ item.divisi.nama_divisi }}</td>
                                        <td class="py-3 px-6">{{ item.kategori.nama_kategori }}</td>
                                        <td class="py-3 px-6 uppercase">{{ item.file_type }}</td>
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <button v-if="item.file_type === 'pdf'" @click="openPdfPreview(item)" class="text-blue-600 hover:text-blue-900 mr-2">Preview</button>
                                            <Link :href="route('arsip.show', item.id)" class="text-indigo-600 hover:text-indigo-900 mr-2">Lihat</Link>
                                            <Link :href="route('arsip.download', item.id)" class="text-green-600 hover:text-green-900 mr-2">Unduh</Link>
                                            <template v-if="auth.user.role === 'admin'">
                                                <Link :href="route('arsip.edit', item.id)" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</Link>
                                                <button @click="deleteArsip(item.id)" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <template v-for="(link, key) in arsip.links" :key="key">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="px-4 py-2 mx-1 text-sm leading-4 rounded"
                                    :class="{ 'bg-blue-500 text-white': link.active, 'bg-white hover:bg-gray-100': !link.active }"
                                    v-html="link.label"
                                />
                                <span v-else class="px-4 py-2 mx-1 text-sm leading-4 text-gray-400" v-html="link.label"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <PdfPreviewModal :show="showPdfModal" :pdf-url="pdfPreviewUrl" :title="pdfPreviewTitle" @close="closePdfPreview" />

    </AuthenticatedLayout>
</template>
