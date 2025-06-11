<!-- resources/js/Components/TableSearch.vue -->
<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <!-- Search Bar -->
      <div class="pb-4 bg-white dark:bg-gray-900">
        <label for="table-search" class="sr-only">Search</label>
        <div class="relative mt-1">
          <div
            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
          >
            <svg
              class="w-4 h-4 text-gray-500 dark:text-gray-400"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 20 20"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
              />
            </svg>
          </div>
          <input
            type="text"
            id="table-search"
            v-model="searchQuery"
            class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Search for items"
          />
        </div>
      </div>

      <!-- Table -->
      <table
        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
      >
        <thead
          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input
                  id="checkbox-all-search"
                  type="checkbox"
                  v-model="selectAll"
                  @change="toggleSelectAll"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label for="checkbox-all-search" class="sr-only">checkbox</label>
              </div>
            </th>
            <th
              v-for="column in columns"
              :key="column.key"
              scope="col"
              class="px-6 py-3"
            >
              {{ column.label }}
            </th>
            <th scope="col" class="px-6 py-3" v-if="routeName">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in filteredItems"
            :key="item.id || index"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
          >
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input
                  :id="`checkbox-table-search-${index}`"
                  type="checkbox"
                  v-model="selectedItems"
                  :value="item.id || index"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label
                  :for="`checkbox-table-search-${index}`"
                  class="sr-only"
                  >checkbox</label
                >
              </div>
            </td>
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-4"
              :class="{ 'font-medium text-gray-900 dark:text-white': column.key === searchField }"
            >
              {{ item[column.key] || '-' }}
            </td>
            <td class="px-6 py-4" v-if="routeName">
              <Link
                :href="route(routeName, item.id || index)"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >Edit</Link
              >
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script>
  import { defineComponent, ref, computed, watch } from 'vue';
  import { Link } from '@inertiajs/vue3';

  export default defineComponent({
    name: 'TableSearch',
    components: {
      Link,
    },
    props: {
      items: {
        type: [Array, Object],
        default: () => [],
      },
      columns: {
        type: Array,
        default: () => [
          { key: 'name', label: 'Product name' },
          { key: 'color', label: 'Color' },
          { key: 'category', label: 'Category' },
          { key: 'price', label: 'Price' },
        ],
      },
      searchField: {
        type: String,
        default: 'name',
      },
      routeName: {
        type: String,
        default: null,
      },
    },
    setup(props) {
      // Reactive state
      const searchQuery = ref('');
      const selectedItems = ref([]);
      const selectAll = ref(false);

      // Normalize items to array
      const normalizedItems = computed(() => {
        if (Array.isArray(props.items)) {
          return props.items;
        }
        if (props.items && typeof props.items === 'object') {
          return Object.values(props.items);
        }
        return [];
      });

      // Filter items based on search
      const filteredItems = computed(() => {
        if (!searchQuery.value) return normalizedItems.value;
        return normalizedItems.value.filter((item) =>
          item[props.searchField]
            ?.toString()
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase())
        );
      });

      // Toggle select all
      const toggleSelectAll = () => {
        if (selectAll.value) {
          selectedItems.value = normalizedItems.value.map((item, index) => item.id || index);
        } else {
          selectedItems.value = [];
        }
      };

      // Watch selectedItems to update selectAll
      watch(selectedItems, (newVal) => {
        selectAll.value =
          newVal.length === normalizedItems.value.length &&
          normalizedItems.value.length > 0;
      });

      return {
        searchQuery,
        selectedItems,
        selectAll,
        normalizedItems,
        filteredItems,
        toggleSelectAll,
      };
    },
  });
  </script>
