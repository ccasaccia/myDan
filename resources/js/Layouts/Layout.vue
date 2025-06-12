<template>
  <div class="h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
    <!-- Navbar (emette evento toggle-sidebar) -->
    <Navbar @toggle-sidebar="toggleSidebar" />

    <!-- Sidebar con riferimento -->
    <Sidebar ref="sidebarRef" class="mt-4" />

    <!-- Contenuto principale -->
    <main class="flex-1 p-4 sm:ml-[360px] mt-14 overflow-hidden">
      <slot />
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Sidebar from '@/Components/Sidebar.vue';
import Footer from '@/Components/Footer.vue';

const sidebarRef = ref(null);

const toggleSidebar = () => {
  sidebarRef.value.toggleSidebar();
};
</script>

<style scoped>
/* Rimuovi scroll dal body */
body {
  overflow: hidden;
}
</style>
