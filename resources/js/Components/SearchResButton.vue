<template>
<div class="flex justify-end mb-4">
  <div class="relative w-64">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
      <button v-if="search" @click="search = ''" class="text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
</div>
</template>

