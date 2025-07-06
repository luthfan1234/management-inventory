// File: resources/js/Components/ManagementModal.vue
// Komponen Modal yang dapat digunakan kembali untuk form Divisi dan Kategori.

<script setup>
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    show: Boolean,
    form: Object,
    title: String,
    nameLabel: String,
    processing: Boolean,
});

const emit = defineEmits(['submit', 'close']);

const submit = () => {
    emit('submit');
};

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" @click.self="closeModal">
        <div class="w-full max-w-lg mx-4 bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">{{ title }}</h2>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-6">
                <div>
                    <InputLabel :for="nameLabel.toLowerCase().replace(' ', '_')" :value="nameLabel" class="text-sm font-semibold text-gray-700 mb-2" />
                    <TextInput
                        :id="nameLabel.toLowerCase().replace(' ', '_')"
                        type="text"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="deskripsi" value="Deskripsi" class="text-sm font-semibold text-gray-700 mb-2" />
                    <textarea
                        id="deskripsi"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        rows="3"
                        v-model="form.deskripsi"
                        placeholder="Masukkan deskripsi..."
                    ></textarea>
                    <InputError class="mt-2" :message="form.errors.deskripsi" />
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" @click="closeModal" class="px-6 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <PrimaryButton :class="{ 'opacity-50 cursor-not-allowed': processing }" :disabled="processing" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                        {{ processing ? 'Menyimpan...' : 'Simpan' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
