// File: resources/js/Pages/Dashboard.vue
// Halaman Dashboard dengan layout baru dan skema warna biru untuk branding KAI.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import BarChart from '@/Components/BarChart.vue';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
});

// Fungsi untuk format bytes
const formatBytes = (bytes, decimals = 2) => {
    if (!bytes || bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

// Fungsi untuk format tanggal
const formatDate = (dateString) => {
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Data untuk chart, warna disesuaikan menjadi biru
const arsipPerDivisiData = computed(() => ({
    labels: props.stats.arsipPerDivisi.map(item => item.nama_divisi),
    datasets: [{
        label: 'Jumlah Arsip',
        backgroundColor: '#3B82F6', // Blue-500
        borderColor: '#2563EB', // Blue-600
        borderWidth: 1,
        borderRadius: 5,
        data: props.stats.arsipPerDivisi.map(item => item.arsip_count),
    }],
}));

const arsipPerKategoriData = computed(() => ({
    labels: props.stats.arsipPerKategori.map(item => item.nama_kategori),
    datasets: [{
        label: 'Jumlah Arsip',
        backgroundColor: '#F97316', // Oranye untuk variasi
        borderColor: '#B45309',
        borderWidth: 1,
        borderRadius: 5,
        data: props.stats.arsipPerKategori.map(item => item.arsip_count),
    }],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                precision: 0
            }
        }
    }
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
             <h1 class="text-2xl font-bold text-gray-800">Dashboard Arsip</h1>
        </template>

        <div class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="material-icons">archive</i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">— Arsip Digital KAI</h3>
                                <p class="text-blue-100 text-sm">DAOP 6 Yogyakarta</p>
                            </div>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold mb-8 leading-tight">
                           Wujudkan dokumentasi yang rapi dengan sistem arsip digital.
                        </h2>
                    </div>
                    <Link :href="route('arsip.create')" class="inline-flex items-center bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-colors self-start">
                        <i class="material-icons mr-2">add_circle</i>
                        Upload Arsip Baru
                    </Link>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                             <p class="text-sm text-gray-500">Total Arsip</p>
                             <i class="material-icons text-gray-300">folder</i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.totalArsip || 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                         <div class="flex justify-between items-center mb-2">
                             <p class="text-sm text-gray-500">Total Divisi</p>
                             <i class="material-icons text-gray-300">business</i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.totalDivisi || 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                         <div class="flex justify-between items-center mb-2">
                             <p class="text-sm text-gray-500">Total Kategori</p>
                             <i class="material-icons text-gray-300">category</i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.totalKategori || 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                         <div class="flex justify-between items-center mb-2">
                             <p class="text-sm text-gray-500">Ukuran File</p>
                             <i class="material-icons text-gray-300">storage</i>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">{{ formatBytes(stats.totalUkuranFile) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Arsip per Divisi</h3>
                <p class="text-gray-600 text-sm mb-4">Distribusi arsip berdasarkan divisi.</p>
                <div class="h-[370px]"> <!-- Tinggi 500px -->
                    <BarChart :chart-data="arsipPerDivisiData" />
                </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Arsip per Kategori</h3>
                <p class="text-gray-600 text-sm mb-4">Distribusi arsip berdasarkan kategori.</p>
                <div class="h-[370px]">
                    <BarChart :chart-data="arsipPerKategoriData" />
                </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Arsip Terbaru</h3>
                        <p class="text-gray-600 text-sm">Dokumen yang baru saja ditambahkan.</p>
                    </div>
                    <Link :href="route('arsip.index')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua
                    </Link>
                </div>
                 <div class="p-4">
                     <div v-if="stats.arsipTerbaru && stats.arsipTerbaru.length > 0" class="space-y-2">
                         <div v-for="item in stats.arsipTerbaru.slice(0, 5)" :key="item.id" class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="material-icons text-blue-600">description</i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ item.judul }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ item.divisi?.nama_divisi || 'Divisi tidak diketahui' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-gray-400">{{ formatDate(item.created_at) }}</p>
                                <Link :href="route('arsip.show', item.id)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Lihat Detail
                                </Link>
                            </div>
                        </div>
                     </div>
                     <div v-else class="text-center py-8">
                         <i class="material-icons text-gray-300 text-4xl mb-2">folder_open</i>
                         <p class="text-gray-500">Belum ada arsip terbaru.</p>
                     </div>
                 </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
