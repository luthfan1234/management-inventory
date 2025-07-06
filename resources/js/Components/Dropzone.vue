// File: resources/js/Components/Dropzone.vue
// Komponen baru untuk fungsionalitas upload file dengan drag & drop.

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const emit = defineEmits(['file-dropped']);

const isDragging = ref(false);
const fileInput = ref(null);
const fileName = ref('');
const file = ref(null);

const handleDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (event) => {
    event.preventDefault();
    isDragging.value = false;
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        selectFile(files[0]);
    }
};

const openFileBrowser = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const files = event.target.files;
    if (files.length > 0) {
        selectFile(files[0]);
    }
};

const selectFile = (selectedFile) => {
    file.value = selectedFile;
    fileName.value = selectedFile.name;
    emit('file-dropped', selectedFile);
};

const removeFile = () => {
    file.value = null;
    fileName.value = '';
    emit('file-dropped', null);
};

const events = ['dragenter', 'dragover', 'dragleave', 'drop'];

onMounted(() => {
    events.forEach((eventName) => {
        document.body.addEventListener(eventName, (e) => e.preventDefault());
    });
});

onUnmounted(() => {
    events.forEach((eventName) => {
        document.body.removeEventListener(eventName, (e) => e.preventDefault());
    });
});
</script>

<template>
    <div>
        <div
            @dragover.prevent="handleDragOver"
            @dragleave.prevent="handleDragLeave"
            @drop.prevent="handleDrop"
            @click="openFileBrowser"
            class="border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-300 hover:shadow-lg"
            :class="{
                'border-blue-400 bg-blue-50 shadow-md': isDragging,
                'border-gray-300 hover:border-blue-400 hover:bg-gray-50': !isDragging && !file,
                'border-green-400 bg-green-50 shadow-md': file
            }"
        >
            <input type="file" ref="fileInput" @change="handleFileChange" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx">

            <div v-if="!file" class="space-y-4">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-700 mb-2">
                        Seret dan lepas file di sini
                    </p>
                    <p class="text-sm text-gray-500 mb-3">atau <span class="text-blue-600 font-medium">klik untuk memilih file</span></p>
                    <p class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full inline-block">PDF, DOC, DOCX, XLS, XLSX (maks. 10MB)</p>
                </div>
            </div>

            <div v-else class="space-y-4">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-800 mb-1">{{ fileName }}</p>
                    <p class="text-sm text-green-600 font-medium mb-3">File berhasil dipilih</p>
                    <button @click.stop="removeFile" class="bg-red-100 hover:bg-red-200 text-red-600 px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                        Hapus File
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

// ---
