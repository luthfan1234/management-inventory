// File: resources/js/Components/PdfPreviewModal.vue
// Komponen baru untuk menampilkan preview PDF dalam sebuah modal.

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  pdfUrl: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    default: 'Preview Dokumen'
  }
});

const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
};
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="closeModal">
    <div class="relative w-full h-full max-w-4xl max-h-[90vh] bg-white rounded-lg shadow-xl flex flex-col">
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
        <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
      <div class="flex-grow p-4">
        <iframe :src="pdfUrl" class="w-full h-full border-0"></iframe>
      </div>
    </div>
  </div>
</template>
