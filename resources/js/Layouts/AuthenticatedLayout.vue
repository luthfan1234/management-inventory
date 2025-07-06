// File: resources/js/Layouts/AuthenticatedLayout.vue
// PERBAIKAN FINAL: Memastikan logo aplikasi adalah link yang fungsional.

<script setup>
import { ref } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';

const page = usePage();
const authUser = page.props.auth.user;

// State untuk menu mobile dan dropdown profil
const showingMobileMenu = ref(false);
const showingProfileDropdown = ref(false);

</script>

<template>
    <Head>
        <title>Arsip Digital</title>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </Head>

    <div class="bg-gray-50 font-sans min-h-screen flex">
        <aside :class="{'translate-x-0': showingMobileMenu, '-translate-x-full': !showingMobileMenu}" class="w-80 flex-shrink-0 flex flex-col bg-white border-r border-gray-200 fixed top-0 h-screen z-40 md:sticky md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">

            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <Link :href="route('dashboard')">
                    <div class="inline-flex items-center space-x-3 select-none">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                            <i class="material-icons text-white text-xl">folder</i>
                        </div>
                        <div>
                            <span class="font-bold text-xl text-gray-800 block">Arsip Digital</span>
                            <span class="text-sm text-gray-500">DAOP 6 Yogyakarta</span>
                        </div>
                    </div>
                </Link>

                <button @click="showingMobileMenu = false" class="md:hidden text-gray-400 hover:text-gray-600">
                    <i class="material-icons text-xl">close</i>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-3">Main Menu</p>

                <Link :href="route('dashboard')"
                      class="flex items-center px-4 py-3 rounded-2xl transition-all group"
                      :class="{
                          'bg-blue-100 text-blue-700': route().current('dashboard'),
                          'text-gray-600 hover:bg-gray-100 hover:text-gray-900': !route().current('dashboard')
                      }">
                    <i class="material-icons mr-3 text-xl">dashboard</i>
                    <span class="font-medium">Dashboard</span>
                </Link>

                <template v-if="authUser.role === 'admin'">
                    <Link :href="route('arsip.index')"
                          class="flex items-center px-4 py-3 rounded-2xl transition-all group"
                          :class="{
                              'bg-blue-100 text-blue-700': route().current().startsWith('arsip.'),
                              'text-gray-600 hover:bg-gray-100 hover:text-gray-900': !route().current().startsWith('arsip.')
                          }">
                        <i class="material-icons mr-3 text-xl">folder_open</i>
                        <span class="font-medium">Arsip Dokumen</span>
                    </Link>

                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-8 mb-4 px-3">Management</p>

                    <Link :href="route('divisi.index')"
                          class="flex items-center px-4 py-3 rounded-2xl transition-all group"
                          :class="{
                              'bg-blue-100 text-blue-700': route().current().startsWith('divisi.'),
                              'text-gray-600 hover:bg-gray-100 hover:text-gray-900': !route().current().startsWith('divisi.')
                          }">
                        <i class="material-icons mr-3 text-xl">business</i>
                        <span class="font-medium">Manajemen Divisi</span>
                    </Link>

                    <Link :href="route('kategori.index')"
                          class="flex items-center px-4 py-3 rounded-2xl transition-all group"
                          :class="{
                              'bg-blue-100 text-blue-700': route().current().startsWith('kategori.'),
                              'text-gray-600 hover:bg-gray-100 hover:text-gray-900': !route().current().startsWith('kategori.')
                          }">
                        <i class="material-icons mr-3 text-xl">local_offer</i>
                        <span class="font-medium">Manajemen Kategori</span>
                    </Link>
                </template>
                </nav>

            <div class="mt-auto"></div>
        </aside>

        <div class="flex-1 flex flex-col overflow-auto">
            <header class="flex items-center justify-between bg-white px-8 py-5 border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center">
                    <button @click="showingMobileMenu = true" class="md:hidden text-gray-500 hover:text-gray-700 mr-4">
                        <i class="material-icons text-xl">menu</i>
                    </button>
                    <div class="flex-1">
                        <slot name="header" />
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                        <i class="material-icons text-xl">notifications</i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="relative">
                        <button @click="showingProfileDropdown = !showingProfileDropdown" class="block">
                            <img :src="`https://ui-avatars.com/api/?name=${authUser.name}&background=3B82F6&color=fff&rounded=true`" alt="User Avatar" class="w-10 h-10 rounded-full object-cover" />
                        </button>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div v-if="showingProfileDropdown" @click.away="showingProfileDropdown = false" class="absolute top-full right-0 mt-3 w-80 bg-white rounded-2xl border border-gray-200 p-4 z-50 shadow-2xl">
                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-2xl mb-3">
                                    <img :src="`https://ui-avatars.com/api/?name=${authUser.name}&background=3B82F6&color=fff&rounded=true`" alt="User Avatar" class="w-12 h-12 rounded-xl object-cover" />
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ authUser.name }}</p>
                                        <p class="text-gray-500 text-xs capitalize">{{ authUser.role }}</p>
                                    </div>
                                    <Link :href="route('profile.edit')" @click="showingProfileDropdown = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="material-icons text-lg">settings</i>
                                    </Link>
                                </div>

                                <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center justify-center bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-2xl transition-all font-medium">
                                    <i class="material-icons mr-2 text-lg">logout</i>
                                    <span>Logout</span>
                                </Link>
                            </div>
                        </transition>
                    </div>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8 flex-1 overflow-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
