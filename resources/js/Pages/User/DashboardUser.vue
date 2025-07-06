// File: resources/js/Pages/User/DashboardUser.vue
// Perbaikan pada properti nama divisi dan penyempurnaan efek hover.

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

// --- State Management ---
const search = ref(props.filters.search);
const divisiId = ref(props.filters.divisi_id);
const kategoriId = ref(props.filters.kategori_id);

// --- PDF Preview Modal ---
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

// --- Filtering Logic ---
watch([search, divisiId, kategoriId], debounce(() => {
    router.get(route('dashboard'), {
        search: search.value,
        divisi_id: divisiId.value,
        kategori_id: kategoriId.value
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}, 300));

// --- Computed URL for Export ---
const exportUrl = computed(() => {
    const params = new URLSearchParams({
        search: search.value || '',
        divisi_id: divisiId.value || '',
        kategori_id: kategoriId.value || ''
    }).toString();
    return `${route('arsip.export')}?${params}`;
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Pencarian Arsip</h1>
        </template>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="material-icons text-3xl">search</i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Selamat Datang, {{ auth.user.name }}!</h3>
                        <p class="text-blue-100 text-sm">Gunakan filter di bawah untuk mencari dokumen yang Anda butuhkan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari judul atau nomor arsip..."
                        class="form-input w-full bg-white/20 border-white/30 rounded-lg placeholder-blue-100 text-white focus:ring-white focus:border-white transition"
                    >
                    <select
                        v-model="divisiId"
                        class="form-select w-full bg-white/20 border-white/30 rounded-lg text-white focus:ring-white focus:border-white transition"
                    >
                        <option :value="null" class="text-black">Semua Divisi</option>
                        <option v-for="d in divisi" :key="d.id" :value="d.id" class="text-black">{{ d.nama }}</option>
                    </select>
                    <select
                        v-model="kategoriId"
                        class="form-select w-full bg-white/20 border-white/30 rounded-lg text-white focus:ring-white focus:border-white transition"
                    >
                        <option :value="null" class="text-black">Semua Kategori</option>
                        <option v-for="k in kategori" :key="k.id" :value="k.id" class="text-black">{{ k.nama_kategori }}</option>
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Hasil Pencarian</h3>
                        <p class="text-gray-600 text-sm mt-1">Menampilkan dokumen arsip sesuai filter.</p>
                    </div>
                    <a :href="exportUrl" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-sm mt-4 sm:mt-0">
                        <i class="material-icons mr-2 text-base">download</i>
                        <span>Export Hasil</span>
                    </a>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Arsip</th>
                                    <th class="py-3 px-5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="py-3 px-5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                                    <th class="py-3 px-5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="py-3 px-5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="!arsip || arsip.data.length === 0">
                                    <td colspan="5" class="text-center py-12 text-gray-500">
                                        <i class="material-icons text-gray-300 text-5xl mb-2">folder_open</i>
                                        <p class="mt-2">Tidak ada data arsip yang cocok dengan pencarian Anda.</p>
                                    </td>
                                </tr>
                                <tr v-else v-for="item in arsip.data" :key="item.id" class="hover:bg-gray-100 transition-colors duration-150">
                                    <td class="py-4 px-5 text-sm whitespace-nowrap text-gray-600">{{ item.nomor_arsip }}</td>
                                    <td class="py-4 px-5 text-sm font-medium text-gray-900">{{ item.judul }}</td>
                                    <td class="py-4 px-5 text-sm text-gray-600">{{ item.divisi.nama }}</td>
                                    <td class="py-4 px-5 text-sm text-gray-600">{{ item.kategori.nama_kategori }}</td>
                                    <td class="py-4 px-5 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button v-if="item.file_type === 'pdf'" @click="openPdfPreview(item)" title="Preview" class="p-2 rounded-full hover:bg-blue-100 text-blue-600 transition-colors">
                                                <i class="material-icons text-xl">visibility</i>
                                            </button>
                                            <Link :href="route('arsip.show', item.id)" title="Lihat Detail" class="p-2 rounded-full hover:bg-indigo-100 text-indigo-600 transition-colors">
                                                <i class="material-icons text-xl">open_in_new</i>
                                            </Link>
                                            <Link :href="route('arsip.download', item.id)" title="Unduh" class="p-2 rounded-full hover:bg-green-100 text-green-600 transition-colors">
                                                 <i class="material-icons text-xl">download</i>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="arsip && arsip.links && arsip.links.length > 3" class="p-6 border-t border-gray-200 bg-gray-50/50 flex justify-end">
                     <template v-for="(link, key) in arsip.links" :key="key">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            preserve-scroll
                            class="px-4 py-2 mx-1 text-sm leading-4 rounded-md transition"
                            :class="{ 'bg-blue-600 text-white shadow-md': link.active, 'bg-white hover:bg-gray-100 text-gray-700 border': !link.active }"
                        >
                           <span v-html="link.label"></span>
                        </Link>
                        <span v-else class="px-4 py-2 mx-1 text-sm leading-4 text-gray-400 rounded-md border" v-html="link.label"></span>
                    </template>
                </div>
            </div>
        </div>

        <PdfPreviewModal :show="showPdfModal" :pdf-url="pdfPreviewUrl" :title="pdfPreviewTitle" @close="closePdfPreview" />
    </AuthenticatedLayout>
</template>
