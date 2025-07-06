// File: resources/js/Components/BarChart.vue
// Komponen untuk menampilkan grafik batang di Dashboard.

<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

defineProps({
    chartData: {
        type: Object,
        required: true,
    },
    chartOptions: {
        type: Object,
        default: () => ({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: {
                            family: 'Inter, sans-serif',
                            size: 12
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }),
    },
});
</script>

<template>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="h-80">
            <Bar :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>
