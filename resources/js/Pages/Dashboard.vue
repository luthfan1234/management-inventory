// File: resources/js/Pages/Dashboard.vue
// Halaman Dashboard dengan statistik dan grafik.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import StatCard from '@/Components/StatCard.vue';
import BarChart from '@/Components/BarChart.vue';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
});

const formatBytes = (bytes, decimals = 2) => {
    if (!bytes || bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

const formatDate = (dateString) => {
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const arsipPerDivisiData = computed(() => ({
    labels: props.stats.arsipPerDivisi.map(item => item.nama_divisi),
    datasets: [{
        label: 'Jumlah Arsip',
        backgroundColor: '#4A90E2',
        data: props.stats.arsipPerDivisi.map(item => item.arsip_count),
    }],
}));

const arsipPerKategoriData = computed(() => ({
    labels: props.stats.arsipPerKategori.map(item => item.nama_kategori),
    datasets: [{
        label: 'Jumlah Arsip',
        backgroundColor: '#50E3C2',
        data: props.stats.arsipPerKategori.map(item => item.arsip_count),
    }],
}));

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <StatCard title="Total Arsip" :value="stats.totalArsip" iconClass="fas fa-file-archive" />
                    <StatCard title="Total Divisi" :value="stats.totalDivisi" iconClass="fas fa-sitemap" />
                    <StatCard title="Total Kategori" :value="stats.totalKategori" iconClass="fas fa-tags" />
                    <StatCard title="Total Ukuran File" :value="formatBytes(stats.totalUkuranFile)" iconClass="fas fa-hdd" />
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="font-semibold mb-4">Arsip per Divisi</h3>
                        <div class="h-64">
                             <BarChart :chart-data="arsipPerDivisiData" />
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="font-semibold mb-4">Arsip per Kategori</h3>
                        <div class="h-64">
                            <BarChart :chart-data="arsipPerKategoriData" />
                        </div>
                    </div>
                </div>

                <!-- Recent Arsip -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Arsip Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Judul</th>
                                        <th class="py-2 px-4 text-left">Divisi</th>
                                        <th class="py-2 px-4 text-left">Tanggal Unggah</th>
                                        <th class="py-2 px-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                     <tr v-if="stats.arsipTerbaru.length === 0">
                                        <td colspan="4" class="text-center py-4">Tidak ada arsip terbaru.</td>
                                    </tr>
                                    <tr v-for="item in stats.arsipTerbaru" :key="item.id" class="border-b">
                                        <td class="py-2 px-4">{{ item.judul }}</td>
                                        <td class="py-2 px-4">{{ item.divisi.nama_divisi }}</td>
                                        <td class="py-2 px-4">{{ formatDate(item.created_at) }}</td>
                                        <td class="py-2 px-4 text-center">
                                            <Link :href="route('arsip.show', item.id)" class="text-indigo-600 hover:text-indigo-900">
                                                Lihat Detail
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
