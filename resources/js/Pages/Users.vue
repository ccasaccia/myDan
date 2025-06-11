<script setup>
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import SearchResButton from '../Components/SearchResButton.vue';
defineProps(['errors', 'users', 'auth']);
const page = usePage();
const pagination = computed(() => usePage().props.users);
const search = ref('');
const paginateButton = ref(true);
const sortField = ref(null);
const sortDirection = ref('asc');

function setSort(field) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
}
</script>

<template>
    <SearchResButton />
<!-- <div class="flex justify-end mb-4">
  <div class="relative w-64">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
      <button v-if="search" @click="search = ''" class="text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
</div> -->
<DataTable
  :pagination="pagination"
  :search="search"
  @navigate="url => router.visit(url)"
  :paginationButton="paginateButton"
  :sortField="sortField"
  :sortDirection="sortDirection"
  @setSort="setSort"
  :enableColumnFilters="false"
  :fields="['name', 'email', 'created_at','updated_at']"
  :headers="['Nome', 'Email', 'Creato il','Aggiornato il']"
  table-class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
  thead-class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
  tbody-class=""
  tr-head-class=""
  tr-body-class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
  th-class="px-6 py-3"
  td-class="px-6 py-4"
/>
<!-- Placeholder per filtri avanzati -->
<!-- <AdvancedFilters :filters="..." @update="..." /> -->
</template>
