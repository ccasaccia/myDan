<template>
    <div class="h-screen overflow-y-auto bg-gray-100 dark:bg-gray-900">
      <!-- Navbar (emette evento toggle-sidebar) -->
      <Navbar @toggle-sidebar="toggleSidebar" />

      <!-- Sidebar con riferimento -->
      <Sidebar ref="sidebarRef" class="mt-4" />

      <!-- Contenuto principale -->
     <main class="p-4 sm:ml-[360px] mt-14">
        <slot />
      </main>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import Navbar from '@/Components/Navbar.vue';
  import Sidebar from '@/Components/Sidebar.vue';

  const sidebarRef = ref(null);

  const toggleSidebar = () => {
    sidebarRef.value.toggleSidebar();
  };
  </script>
