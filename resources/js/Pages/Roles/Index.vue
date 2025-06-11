<script setup>
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import  DataTable  from '@/Components/DataTable.vue';
import SearchResButton from '../../Components/SearchResButton.vue';
defineProps([
    'roles',
    'filters',
    'can',
]);
const { props } = usePage();
const pagination = computed(() => props.roles);
const search = ref('');
const paginatieButton = ref(true);
const sortField = ref(null);
const sortDirection = ref(null);

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
    { label: ['fas','plus'], route: 'roles.create', type: 'success' }
  ]"
  :pagination="pagination"
  :search="search"
  @navigate="url => router.visit(url)"
  :paginationButton="paginateButton"
  :sortField="sortField"
  :sortDirection="sortDirection"
  @setSort="setSort"
  :enableColumnFilters="false"
  :fields="['id', 'name', 'guard_name', 'created_at','']"
  :headers="['Id', 'Nome', 'Guard Name', 'Creato il','Azioni']"
  table-class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
  thead-class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
  tbody-class=""
  tr-head-class=""
  tr-body-class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
  th-class="px-6 py-3"
  td-class="px-6 py-4"
/>
</template>
<style></style>
