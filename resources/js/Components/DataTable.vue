<template>
<div class="relative overflow-x-auto shadow ring-1 ring-gray-300 sm:rounded-lg">
  <div class="flex justify-between items-center mt-4 mb-4 px-4">
    <div class="flex items-center gap-2">
      <div class="relative w-64">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
          <svg v-if="!searchText" class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M13.293 14.707a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.414l-4.387-4.386zM8 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
          </svg>
          <button v-else @click="searchText = ''" class="text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <input
          v-model="searchText"
          type="text"
          placeholder="Cerca..."
          class="block w-full pl-10 pr-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        />
      </div>

      <button
        @click="showFilters = !showFilters"
        class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 border rounded"
        title="Mostra/Nascondi filtri"
      >
        <font-awesome-icon :icon="['fas', 'filter']" class="text-blue-500"/>
      </button>
    </div>

    <div class="flex space-x-2">
      <Link
        v-for="(button, index) in customButtons"
        :key="index"
        :href="route(button.route)"
        class="px-3 py-2 text-sm text-white rounded"
        :class="{
          'bg-green-500 hover:bg-green-600': button.type === 'success',
          'bg-yellow-500 hover:bg-yellow-600': button.type === 'warning',
          'bg-red-500 hover:bg-red-600': button.type === 'danger',
          'bg-blue-500 hover:bg-blue-600': button.type === 'info'
        }"
      >
        <template v-if="Array.isArray(button.label)">
          <font-awesome-icon :icon="button.label" />
        </template>
        <template v-else>
          {{ button.label }}
        </template>
      </Link>
    </div>
  </div>
  <table :class="tableClass">
    <thead :class="theadClass">
      <tr v-if="!showFilters" :class="trHeadClass">
        <th
          v-for="(header, index) in headers"
          :key="index"
          :class="thClass"
          @click="$emit('setSort', fields[index])"
          class="cursor-pointer select-none"
        >
          {{ header }}
          <span v-if="sortField === fields[index]">
            {{ sortDirection === 'asc' ? '▲' : '▼' }}
          </span>
        </th>
      </tr>
      <tr v-if="showFilters">
        <th v-for="(field, index) in fields" :key="field" class="p-2">
          <input
            v-model="columnFilters[field]"
            type="text"
            class="w-full px-2 py-1 text-sm border rounded"
            :placeholder="headers[index]"
          />
        </th>
      </tr>
    </thead>
    <tbody :class="tbodyClass">
      <tr v-if="filteredData.length === 0">
        <td :colspan="fields.length" class="text-center py-4 text-gray-500">
          Nessun risultato trovato.
        </td>
      </tr>
      <tr v-for="(item, index) in filteredData" :key="index" :class="trBodyClass">
        <td v-for="key in fields" :key="key" :class="tdClass">
            {{ key === 'created_at' || key === 'updated_at' ? $getDate(item[key]) : item[key] }}
        </td>
      </tr>
    </tbody>
  </table>
  <!-- <div v-if="pagination?.total" class="px-4 py-2 text-sm text-gray-600">
    Mostrando {{ pagination.from }}-{{ pagination.to }} di {{ pagination.total }}
  </div> -->
  <div v-if="pagination?.links" class="flex flex-col items-center justify-between px-4 py-4 space-y-2 sm:flex-row sm:space-y-0 sm:space-x-4">

    <button v-if="paginationButton"
      @click="$emit('navigate', pagination.prev_page_url)"
      :disabled="!pagination.prev_page_url"
      class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded disabled:opacity-50"
    >
      Indietro
    </button>

    <div v-if="paginationButton" class="flex flex-wrap justify-center gap-1">
      <div v-if="pagination?.total" class="px-4 py-2 text-sm text-gray-600">
    Result {{ pagination.from }}-{{ pagination.to }} to {{ pagination.total }}
  </div>
    </div>
    <div v-else class="flex flex-wrap justify-center gap-1">
      <button
        v-for="(link, index) in pagination.links"
        :key="index"
        v-html="link.label"
        :disabled="!link.url"
        @click="$emit('navigate', link.url)"
        class="px-3 py-1 text-sm rounded border"
        :class="{
          'bg-blue-500 text-white': link.active,
          'bg-white text-gray-700 hover:bg-gray-100': !link.active
        }"
      />
      <div v-if="pagination?.total" class="px-4 py-2 text-sm text-gray-600">
    Result {{ pagination.from }}-{{ pagination.to }} to {{ pagination.total }}
  </div>
    </div>

    <button v-if="paginationButton"
      @click="$emit('navigate', pagination.next_page_url)"
      :disabled="!pagination.next_page_url"
      class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded disabled:opacity-50"
    >
      Avanti
    </button>
  </div>
</div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

export default {
  components: {
    FontAwesomeIcon,
  },
  props: {
    pagination: {
      type: Object,
      required: true
    },
    paginationButton: {
      type: Boolean,
      default: true
    },
    fields: {
      type: Array,
      required: true
    },
    headers: {
      type: Array,
      default() {
        return this.fields;
      }
    },
    tableClass: {
      type: String,
      default: 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400'
    },
    theadClass: {
      type: String,
      default: 'text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400'
    },
    tbodyClass: {
      type: String,
      default: ''
    },
    trHeadClass: {
      type: String,
      default: ''
    },
    trBodyClass: {
      type: String,
      default: 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"'
    },
    thClass: {
      type: String,
      default: 'px-6 py-3'
    },
    tdClass: {
      type: String,
      default: 'px-6 py-4'
    },
    /* Removed unused prop 'search' */
    sortField: {
      type: String,
      default: null
    },
    sortDirection: {
      type: String,
      default: 'asc'
    },
    enableColumnFilters: {
      type: Boolean,
      default: false
    },
    customButtons: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      searchText: '',
      columnFilters: {},
      showFilters: this.enableColumnFilters
    };
  },
  watch: {
    enableColumnFilters: {
      immediate: true,
      handler(val) {
        this.showFilters = val;
      }
    },
    fields: {
      immediate: true,
      handler(newFields) {
        newFields.forEach(field => {
          if (!(field in this.columnFilters)) {
              this.columnFilters[field] = '';
          }
        });
      }
    }
  },
  computed: {
    sortedData() {
      if (!this.sortField || !this.sortDirection) return this.pagination?.data || [];

      return [...this.pagination.data].sort((a, b) => {
        const aVal = a[this.sortField] ?? '';
        const bVal = b[this.sortField] ?? '';
        return this.sortDirection === 'asc'
          ? String(aVal).localeCompare(String(bVal))
          : String(bVal).localeCompare(String(aVal));
      });
    },
    filteredData() {
      let data = this.sortedData;

      // Applica ricerca globale
      if (this.searchText) {
        data = data.filter(item =>
          Object.values(item).some(val =>
            String(val).toLowerCase().includes(this.searchText.toLowerCase())
          )
        );
      }

      // Applica filtri per colonna
      Object.entries(this.columnFilters).forEach(([field, value]) => {
        if (value) {
          data = data.filter(item =>
            String(item[field]).toLowerCase().includes(value.toLowerCase())
          );
        }
      });

      return data;
    }
  }
};
</script>
