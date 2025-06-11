<template>
    <li>
      <Link
        :href="route(link)"
        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
      >
      <font-awesome-icon v-if="Array.isArray(icon)"
        :icon="icon"
        class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
        />
        <i v-else :class="['pi', icon, 'w-5 h-5 text-gray-500 ...']" />
        <span class="flex-1 ms-3 whitespace-nowrap">{{ name }}</span>
        <span
          v-if="badge"
          class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"
        >
          {{ badge }}
        </span>
      </Link>
    </li>
  </template>

  <script setup>
  import { Link } from '@inertiajs/vue3';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

  defineProps({
    name: {
      type: String,
      required: true,
    },
    link: {
      type: String,
      required: true,
    },
    icon: {
      type: [String, Array],
      required: true,
    },
    badge: {
      type: String,
      required: false,
      default: '',
    },
    style: {
      type: String,
      required: false,
      default: 'font-size: 1.5rem',
    },
  });
  </script>
