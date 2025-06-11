<template>
<Head  :title="` | ${$page.component}`" />
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="bg-slate-300">
                    <th v-for="column in columns" :key="column" scope="col" class="px-6 py-3">{{column.name}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.data" :key="user.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">
                        <img :src=" user.avarar ? ('storage/'+user.avatar) : 'images/default.png'" alt="Avatar" class="h-10 w-10" />
                    </td>
                    <td class="px-6 py-4">
                        {{ user.name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ user.email }}
                    </td>
                    <td class="px-6 py-4">
                        {{ getDate(user.created_at) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <!-- Pagination Links -->
            <!-- <Link v-for="link in users.links" :key="link.label" :to="link.url" v-html="link.label" :href="link"></Link> -->
        </div>
    </div>
    </div>
</template>
<script>
import { ref } from 'vue';
import Layout from '../Layouts/Layout.vue';

export default {
  // Registrazione del componente Layout
  components: {
    Layout,
  },

  // Definizione delle props
  props: {
    users: {
      type: Object,
      required: true,
      default: () => ({}), // Valore di default per evitare errori
    },
  },

  // Funzione setup per la Composition API
  setup(props) {
    // Definizione di myDate (mancava nel codice originale)
    const myDate = ref('2025-04-12'); // Esempio di data, modifica secondo necessità

    const columns = [
        { name: 'Avatar', field: 'avatar' },
        { name: 'Nome', field: 'name' },
        { name: 'Email', field: 'email' },
        { name: 'Data di registrazione', field: 'created_at' },
    ];

    // Funzione getDate
    const getDate = (date) => {
      return new Date(date).toLocaleDateString('en-us', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    };

    // Esposizione al template
    return {
      myDate,
      getDate,
      columns,
    };
  },
};
</script>

<style lang="">

</style>
