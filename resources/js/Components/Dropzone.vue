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
            class="border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors duration-300"
            :class="{
                'border-blue-500 bg-blue-50': isDragging,
                'border-gray-300 hover:border-gray-400': !isDragging && !file,
                'border-green-500 bg-green-50': file
            }"
        >
            <input type="file" ref="fileInput" @change="handleFileChange" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx">

            <div v-if="!file">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <span class="font-semibold">Klik untuk memilih</span> atau seret dan lepas file di sini
                </p>
                <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX (maks. 10MB)</p>
            </div>
            <div v-else>
                 <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 font-semibold text-gray-800">{{ fileName }}</p>
                <p class="text-sm text-gray-600">File berhasil dipilih.</p>
                <button @click.stop="removeFile" class="mt-2 text-xs text-red-500 hover:text-red-700 font-semibold">Hapus File</button>
            </div>
        </div>
    </div>
</template>

// ---
