<template>
    <nav
      :class="minimal
        ? 'flex px-5 py-2 text-gray-400'
        : 'flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700'"
      aria-label="Breadcrumb"
    >
      <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse bg-transparent">
        <li class="inline-flex items-center">
          <Link
            :href="route('home')"
            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
          >
            <svg
              class="w-3 h-3 me-2.5"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
              />
            </svg>
            Home
          </Link>
        </li>

        <li
          v-for="(crumb, index) in breadcrumbs"
          :key="index"
          :aria-current="crumb.current ? 'page' : null"
        >
          <div class="flex items-center">
            <svg
              class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 6 10"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 9 4-4-4-4"
              />
            </svg>

            <Link
              v-if="!crumb.current"
              :href="crumb.path"
              class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white"
            >
              {{ crumb.label }}
            </Link>

            <span
              v-else
              class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400"
            >
              {{ crumb.label }}
            </span>
          </div>
        </li>
      </ol>
    </nav>
  </template>

  <script setup>
  defineProps({
    minimal: {
      type: Boolean,
      default: false
    }
  });

  import { usePage } from '@inertiajs/vue3';
  import { computed } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import { route } from 'ziggy-js';

  const page = usePage();

  const formatLabel = (segment) => {
    return segment
      .replace(/-/g, ' ')
      .replace(/\b\w/g, (l) => l.toUpperCase());
  };

  const breadcrumbs = computed(() => {
    const path = page.url;
    const pathArray = path.split('/').filter(Boolean);

    const filteredPathArray = pathArray.filter(segment => isNaN(segment));

    return filteredPathArray.map((segment, index) => {
      const fullPath = '/' + pathArray.slice(0, pathArray.indexOf(segment) + 1).join('/');

      return {
        label: formatLabel(segment),
        path: fullPath,
        current: index === filteredPathArray.length - 1,
      };
    });
  });
  </script>
