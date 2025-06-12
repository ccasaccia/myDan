<!-- resources/js/Components/Sidebar.vue -->
<template>
  <aside
    class="fixed bottom-[-60px] left-[60px] z-40 w-[300px] min-h-screen transition-transform bg-sidebar-pattern"
    :class="{ '-translate-x-full': !isSidebarOpen, 'sm:translate-x-0': true }"
    aria-label="Sidebar"
  >
    <div class="h-full px-4 pt-40 pb-4 overflow-y-auto">
      <ul class="space-y-4">
        <navBarButton
          v-for="(button, index) in buttonDetails"
          :key="index"
          :label="button.label"
          :active="button.active"
          @click="navigateTo(button.navigateTo)"
        />
      </ul>
    </div>
  </aside>
</template>

<script setup>
import { ref, defineExpose } from 'vue';
import navBarButton from './navBarButton.vue';

const isSidebarOpen = ref(false);

const active = ref(false);

const buttonDetails = ref([
    { label: 'Dashboard', active: false, navigateTo: 'dashboard' },
    { label: 'Iscrizione e assicurazione', active: true, navigateTo: 'iscrizione' },
    { label: 'Account', active: false, navigateTo: 'account' },
    { label: 'Programma Partner', active: false, navigateTo: 'partner' }
]);

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
