// File: resources/js/Components/StatCard.vue
// Komponen baru untuk menampilkan statistik di Dashboard KAI.

<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    colorClass: String,
    trend: String,
    trendValue: String,
});
</script>

<template>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 mb-2">{{ title }}</p>
                <div class="flex items-baseline space-x-2">
                    <p class="text-3xl font-bold text-gray-900">{{ value }}</p>
                    <div v-if="trend" class="flex items-center space-x-1">
                        <i class="material-icons text-sm text-green-500">trending_up</i>
                        <span class="text-sm font-medium text-green-600">{{ trendValue }}</span>
                        <span class="text-xs text-gray-500">dari bulan sebelumnya</span>
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" :class="colorClass">
                <i class="material-icons text-white text-xl">{{ icon }}</i>
            </div>
        </div>
    </div>
</template>
