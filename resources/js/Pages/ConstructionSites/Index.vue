<script setup>
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import SearchResButton from '../../Components/SearchResButton.vue';
defineProps(['errors', 'constructionSites', 'auth']);
const page = usePage();
const pagination = computed(() => usePage().props.constructionSites);
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
<DataTable
:customButtons="[
    { label: ['fas','plus'], route: 'construction-sites.create', type: 'success' }
  ]"
  :pagination="pagination"
  :search="search"
  @navigate="url => router.visit(url)"
  :paginationButton="paginateButton"
  :sortField="sortField"
  :sortDirection="sortDirection"
  @setSort="setSort"
  :enableColumnFilters="false"
  :fields="['site_name', 'client_name', 'location', 'start_date','end_date','is_active']"
  :headers="['Nome Cantiere', 'Cliente', 'Luogo', 'Data Inizio','Data Fine','Attivo']"
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
