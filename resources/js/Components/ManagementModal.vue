/ File: resources/js/Components/ManagementModal.vue
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
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click.self="closeModal">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-xl">
            <h2 class="text-2xl font-bold mb-4">{{ title }}</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel :for="nameLabel.toLowerCase().replace(' ', '_')" :value="nameLabel" />
                    <TextInput
                        :id="nameLabel.toLowerCase().replace(' ', '_')"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="deskripsi" value="Deskripsi" />
                    <textarea
                        id="deskripsi"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.deskripsi"
                    ></textarea>
                    <InputError class="mt-2" :message="form.errors.deskripsi" />
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeModal" class="text-gray-600 hover:text-gray-900 mr-4">
                        Batal
                    </button>
                    <PrimaryButton :class="{ 'opacity-25': processing }" :disabled="processing">
                        Simpan
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
