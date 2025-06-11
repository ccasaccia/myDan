<!-- resources/js/Components/Sidebar.vue -->
<template>
  <aside
    class="fixed bottom-[-60px] left-[60px] z-40 w-[300px] min-h-screen transition-transform bg-sidebar-pattern"
    :class="{ '-translate-x-full': !isSidebarOpen, 'sm:translate-x-0': true }"
    aria-label="Sidebar"
  >
    <div class="h-full px-4 pt-20 pb-4 overflow-y-auto">
      <ul class="space-y-4">
        <navBarButton label="Dashboard" :active="active" @click="navigateTo('dashboard')" />
        <navBarButton label="Iscrizione e assicurazione" :active="true" @click="navigateTo('iscrizione')" />
        <navBarButton label="Account" :active="active" @click="navigateTo('account')" />
        <navBarButton label="Programma Partner" :active="active" @click="navigateTo('partner')" />
      </ul>
    </div>
  </aside>
</template>

<script setup>
import { ref, defineExpose } from 'vue';
import navBarButton from './navBarButton.vue';

const isSidebarOpen = ref(false);

const active = ref(false);

const navigateTo = (section) => {
    console.log(`Navigating to ${section}`);

}

// const menus = [
//     {
//         name: 'Home',
//         link: 'home',
//         icon: ['fas', 'house'],
//         badge: null
//     },

//     {
//         name: 'Products',
//         link: 'products.show',
//         icon: ['fas', 'user-group'],
//         badge: null
//     },


// ];

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

defineExpose({ toggleSidebar });
</script>
<style>

</style>
