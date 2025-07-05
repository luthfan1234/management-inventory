// File: resources/js/Components/StatCard.vue
// Komponen baru untuk menampilkan statistik di Dashboard.

<script setup>
defineProps({
    title: String,
    value: [String, Number],
    iconClass: String,
});
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <div class="bg-blue-100 p-3 rounded-full mr-4">
            <i :class="iconClass" class="text-2xl text-blue-500"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">{{ title }}</p>
            <p class="text-2xl font-bold">{{ value }}</p>
        </div>
    </div>
</template>
