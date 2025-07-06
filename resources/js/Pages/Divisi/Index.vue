// File: resources/js/Pages/Divisi/Index.vue
// Halaman untuk manajemen CRUD Divisi.

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ManagementModal from '@/Components/ManagementModal.vue';

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
        form.name = item.nama_divisi;
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
    };
    if (isEditMode.value) {
        form.transform(data => ({ ...data, nama_divisi: data.name })).put(route('divisi.update', form.id), options);
    } else {
        form.transform(data => ({ ...data, nama_divisi: data.name })).post(route('divisi.store'), options);
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Divisi</h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 text-gray-900">
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                    {{ $page.props.flash.error }}
                </div>

                <PrimaryButton @click="openModal()">Tambah Divisi</PrimaryButton>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-3 px-6 text-left">Nama Divisi</th>
                                <th class="py-3 px-6 text-left">Deskripsi</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr v-if="divisi.length === 0">
                                <td colspan="3" class="text-center py-4">Tidak ada data divisi.</td>
                            </tr>
                            <tr v-for="item in divisi" :key="item.id" class="border-b">
                                <td class="py-3 px-6">{{ item.nama_divisi }}</td>
                                <td class="py-3 px-6">{{ item.deskripsi }}</td>
                                <td class="py-3 px-6 text-center">
                                    <button @click="openModal(item)" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</button>
                                    <button @click="deleteItem(item.id)" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
