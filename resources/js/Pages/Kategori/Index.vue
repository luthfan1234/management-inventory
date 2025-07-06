// File: resources/js/Pages/Kategori/Index.vue
// Halaman untuk manajemen CRUD Kategori dengan gaya modern.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ManagementModal from '@/Components/ManagementModal.vue'; // Asumsi modal ini sudah ada

const props = defineProps({
    kategori: Array,
    errors: Object,
});

const isModalOpen = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null,
    name: '',
    deskripsi: '',
});

const openModal = (item = null) => {
    isModalOpen.value = true;
    if (item) {
        isEditMode.value = true;
        form.id = item.id;
        form.name = item.nama_kategori;
        form.deskripsi = item.deskripsi;
    } else {
        isEditMode.value = false;
        form.reset();
    }
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    const options = {
        onSuccess: () => closeModal(),
        preserveScroll: true,
    };
    if (isEditMode.value) {
        form.transform(data => ({ ...data, nama_kategori: data.name })).put(route('kategori.update', form.id), options);
    } else {
        form.transform(data => ({ ...data, nama_kategori: data.name })).post(route('kategori.store'), options);
    }
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        router.delete(route('kategori.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Manajemen Kategori" />

    <AuthenticatedLayout>
        <template #header>
             <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Manajemen Kategori</h2>
                    <p class="text-sm text-gray-500">Kelola semua kategori dokumen yang terdaftar dalam sistem.</p>
                </div>
            </div>
        </template>

        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 p-4 bg-green-100 text-green-800 border border-green-200 rounded-lg">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 p-4 bg-red-100 text-red-800 border border-red-200 rounded-lg">
            {{ $page.props.flash.error }}
        </div>
        <div class="mt-4 md:mt-0 pb-5">
                    <button @click="openModal()" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                         <i class="material-icons text-base mr-2">add</i>
                        <span>Tambah Kategori</span>
                    </button>
                </div>
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="kategori.length === 0">
                            <td colspan="3" class="text-center py-10 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="material-icons text-4xl text-gray-300 mb-2">category</i>
                                    <span>Tidak ada data kategori.</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="item in kategori" :key="item.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium text-gray-900">{{ item.nama_kategori }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 truncate max-w-md">{{ item.deskripsi || '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <div class="flex items-center justify-center space-x-4">
                                    <button @click="openModal(item)" class="text-gray-500 hover:text-yellow-600 transition-colors" title="Edit">
                                        <i class="material-icons text-lg">edit</i>
                                    </button>
                                    <button @click="deleteItem(item.id)" class="text-gray-500 hover:text-red-600 transition-colors" title="Hapus">
                                        <i class="material-icons text-lg">delete</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ManagementModal
            :show="isModalOpen"
            :form="form"
            :title="isEditMode ? 'Edit Kategori' : 'Tambah Kategori Baru'"
            nameLabel="Nama Kategori"
            :processing="form.processing"
            @submit="submit"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>
