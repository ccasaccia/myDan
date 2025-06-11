<template lang="">
  <button
    @click="$emit('click')"
    class="w-full h-[82px] rounded-md font-semibold text-[16px] transition-all duration-200 border"
    :class="[
      active
        ? 'bg-red-600 text-white border-transparent'
        : 'bg-white text-gray-900 border-gray-300 hover:bg-gray-100'
    ]"
  >
    {{ label }}
  </button>
     <slot></slot>
</template>
<script setup>
defineProps({
    label: {
        type: String,
        default: 'button',
        active: Boolean,
    },
    active: {
        type: Boolean,
        default: false,
    }
});
defineEmits(['click']);
</script>
<style lang="">

</style>
