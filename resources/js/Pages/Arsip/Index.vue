// File: resources/js/Pages/Arsip/Index.vue
// Halaman utama untuk menampilkan, mencari, dan memfilter daftar arsip dengan gaya baru.

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
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Arsip Dokumen</h2>
                    <p class="text-sm text-gray-500">Kelola dan cari semua dokumen digital di sini.</p>
                </div>
            </div>
        </template>

        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 p-4 bg-green-100 text-green-800 border border-green-200 rounded-lg">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 p-4 bg-red-100 text-red-800 border border-red-200 rounded-lg">
            {{ $page.props.flash.error }}
        </div>
        <div class="flex items-center space-x-2 mt-4 md:mt-0 pb-5">
            <a :href="exportUrl" class="inline-flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-700 font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="material-icons text-base mr-2">download</i>
                    <span>Export</span>
            </a>
                <Link v-if="auth.user.role === 'admin'" :href="route('arsip.create')" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    <i class="material-icons text-base mr-2">add</i>
                        <span>Tambah Arsip</span>
                </Link>
        </div>

        <div class="bg-white rounded-2xl p-6 mb-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter & Pencarian</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" v-model="search" placeholder="Cari judul atau nomor arsip..." class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <select v-model="divisiId" class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option :value="null">Semua Divisi</option>
                    <option v-for="d in divisi" :key="d.id" :value="d.id">{{ d.nama}}</option>
                </select>
                <select v-model="kategoriId" class="w-full bg-gray-100 border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option :value="null">Semua Kategori</option>
                    <option v-for="k in kategori" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
             <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Dokumen</h3>
                <p class="text-sm text-gray-500 mt-1">Menampilkan {{ arsip.from }} - {{ arsip.to }} dari {{ arsip.total }} hasil.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Arsip</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe File</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="arsip.data.length === 0">
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="material-icons text-4xl text-gray-300 mb-2">folder_off</i>
                                    <span>Tidak ada data arsip yang ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="item in arsip.data" :key="item.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.nomor_arsip }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.divisi.nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.kategori.nama_kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">{{ item.file_type }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    <button v-if="item.file_type === 'pdf'" @click="openPdfPreview(item)" class="text-gray-500 hover:text-blue-600 transition-colors" title="Preview">
                                        <i class="material-icons text-lg">visibility</i>
                                    </button>
                                    <Link :href="route('arsip.show', item.id)" class="text-gray-500 hover:text-indigo-600 transition-colors" title="Lihat Detail">
                                        <i class="material-icons text-lg">info</i>
                                    </Link>
                                    <Link :href="route('arsip.download', item.id)" class="text-gray-500 hover:text-green-600 transition-colors" title="Unduh">
                                        <i class="material-icons text-lg">download</i>
                                    </Link>
                                    <template v-if="auth.user.role === 'admin'">
                                        <Link :href="route('arsip.edit', item.id)" class="text-gray-500 hover:text-yellow-600 transition-colors" title="Edit">
                                            <i class="material-icons text-lg">edit</i>
                                        </Link>
                                        <button @click="deleteArsip(item.id)" class="text-gray-500 hover:text-red-600 transition-colors" title="Hapus">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

             <div v-if="arsip.links.length > 3" class="p-6 border-t border-gray-200">
                 <div class="flex justify-end">
                     <template v-for="(link, key) in arsip.links" :key="key">
                         <Link
                             v-if="link.url"
                             :href="link.url"
                             class="px-4 py-2 mx-1 text-sm leading-4 rounded-md border transition-colors"
                             :class="{
                                'bg-blue-600 border-blue-600 text-white': link.active,
                                'bg-white border-gray-300 text-gray-700 hover:bg-gray-100': !link.active
                             }"
                             v-html="link.label"
                         />
                         <span v-else class="px-4 py-2 mx-1 text-sm leading-4 text-gray-400 border rounded-md" v-html="link.label"></span>
                     </template>
                 </div>
             </div>
        </div>

        <PdfPreviewModal :show="showPdfModal" :pdf-url="pdfPreviewUrl" :title="pdfPreviewTitle" @close="closePdfPreview" />

    </AuthenticatedLayout>
</template>
