<!-- resources/js/Components/Navbar.vue -->
<template>
    <nav class="fixed top-0 left-0 right-0 z-50 h-16 dark:bg-gray-900 dark:border-gray-700">
        <div class="px-4 py-3 lg:px-6 lg:pl-4">
            <div class="flex items-center">
                <!-- Toggle Sidebar Button (Mobile) -->
                <button @click="$emit('toggle-sidebar')" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="logo-sidebar" :aria-expanded="isSidebarOpen">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>

                <!-- Logo -->

                <div class="pl-[60px] pt-10 flex items-center h-full">
                    <a href="https://daneurope.org" class="flex items-center">
                        <img src="/images/DANlogo.svg" class="w-[183px] h-[56px] opacity-100" alt="Dan Europe Logo" />
                        <span
                            class="font-poppins font-[800] text-4xl ml-4 whitespace-nowrap dark:text-white hidden sm:inline">
                            <!-- <span class="ml-4 text-4xl font-poppins font-bold whitespace-nowrap dark:text-white hidden sm:inline"> -->
                            MyDAN
                        </span>
                    </a>
                </div>


                <!-- Navigation Links -->
                <div class="flex items-center space-x-6 ml-auto">
                    <!-- General Links -->
                    <div class="flex space-x-6">
                    </div>

                    <!-- Authenticated User Links -->
                    <div v-if="$page.props.auth.user" class="flex items-center space-x-6">
                        <img class="w-10 h-10 rounded-full object-cover"
                            :src="$page.props.auth.user.avatar ? `/storage/${$page.props.auth.user.avatar}` : '/images/default.png'"
                            alt="Avatar" />
                        <Link :href="route('logout')" method="post" as="button" type="button"
                            class="cursor-pointer text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded-lg">
                        <span class="text-red-700 dark:text-red-300"> <font-awesome-icon
                                :icon="['fas', 'arrow-right-from-bracket']" /> </span>
                        </Link>
                    </div>

                    <!-- Guest Links -->
                    <div v-else class="flex space-x-6 border !border-red-500 redBorder p-2">
                        <font-awesome-icon :icon="['fas', 'arrow-right-from-bracket']" />
                    </div>

                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Breadcrumb from './Breadcrumb.vue';

// Stato per il toggle della sidebar
const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;


};

// Accedi alle props di Inertia
const $page = usePage();

// Passa lo stato della sidebar come evento se necessario
defineEmits(['toggle-sidebar']);
</script>
<style>
.redBorder {
    border: 2px solid red;
}
</style>
