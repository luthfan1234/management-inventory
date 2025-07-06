// File: resources/js/Pages/Divisi/Index.vue
// Halaman untuk manajemen CRUD Divisi dengan gaya modern.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ManagementModal from '@/Components/ManagementModal.vue'; // Asumsi modal ini sudah ada

const props = defineProps({
    divisi: Array,
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
        form.name = item.nama;
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
        form.transform(data => ({ ...data, nama: data.name })).put(route('divisi.update', form.id), options);
    } else {
        form.transform(data => ({ ...data, nama: data.name })).post(route('divisi.store'), options);
    }
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus divisi ini?')) {
        router.delete(route('divisi.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Manajemen Divisi" />

    <AuthenticatedLayout>
        <template #header>
             <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Manajemen Divisi</h2>
                    <p class="text-sm text-gray-500">Kelola semua divisi yang terdaftar dalam sistem.</p>
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
                        <span>Tambah Divisi</span>
                    </button>
                </div>
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Divisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="divisi.length === 0">
                            <td colspan="3" class="text-center py-10 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="material-icons text-4xl text-gray-300 mb-2">apartment</i>
                                    <span>Tidak ada data divisi.</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="item in divisi" :key="item.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium text-gray-900">{{ item.nama}}</p>
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
            :title="isEditMode ? 'Edit Divisi' : 'Tambah Divisi Baru'"
            nameLabel="Nama Divisi"
            :processing="form.processing"
            @submit="submit"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>
