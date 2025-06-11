<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-center items-center">
      <div class="bg-white p-6 rounded-lg shadow-lg w-[30rem]">
        <h3 class="text-lg font-bold mb-4">{{ title }}</h3>

        <form @submit.prevent="handleSubmit">
          <div v-for="field in fields" :key="field.id" class="mb-4">
            <InputField
              :id="field.id"
              :name="field.name"
              :label="field.label"
              :elementType="field.elementType"
              :type="field.type"
              v-model="formData[field.name]"
            />
          </div>

          <div class="flex justify-end gap-2">
            <button type="button" @click="$emit('close')" class="px-3 py-1 text-sm bg-gray-300 rounded">
              <font-awesome-icon :icon="['fas', 'rotate-left']" />
            </button>
            <button
              v-if="showDelete"
              type="button"
              @click="$emit('delete')"
              class="px-3 py-1 text-sm bg-red-600 text-white rounded"
            >
              <font-awesome-icon :icon="['fas', 'trash-can']" />
            </button>
            <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded">
              <font-awesome-icon :icon="['fas', 'floppy-disk']" />
            </button>
          </div>
        </form>

      </div>
    </div>
  </template>

  <script setup>
  import { reactive, watch } from 'vue'
  import InputField from '@/Components/InputField.vue'

  const props = defineProps({
    show: Boolean,
    title: String,
    fields: Array,
    modelValue: Object,
    showDelete: { type: Boolean, default: false },
  })

  const emit = defineEmits(['update:modelValue', 'submit', 'close', 'delete'])

  const formData = reactive({})

  // Sincronizza i dati esterni con il form interno
  watch(() => props.modelValue, (newVal) => {
    Object.assign(formData, newVal)
  }, { immediate: true })

  watch(() => props.show, (newVal) => {
    if (!newVal) {
      Object.keys(formData).forEach(key => formData[key] = '')
    }
  })

  function handleSubmit() {
    emit('update:modelValue', { ...formData })
    emit('submit', formData)
  }
  </script>
