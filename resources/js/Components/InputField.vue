<template>
    <div>
      <!-- Label sopra per input, select e file; accanto per checkbox -->
      <label
        v-if="elementType !== 'checkbox'"
        :for="id"
        class="block text-sm font-medium text-gray-900"
      >
        {{ label }}
      </label>

      <div :class="{ 'mt-1': elementType !== 'checkbox', 'flex items-center mb-4': elementType === 'checkbox' }">
        <!-- Input (testo, email, password, ecc.) -->
        <input
          v-if="elementType === 'input'"
          :type="type"
          :id="id"
          :name="name"
          v-model="internalValue"
          :autocomplete="autocomplete"
          :class="[
            'block w-full rounded-md border placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500',
            type === 'color'
              ? 'h-10 bg-white text-gray-900 border-gray-300'
              : 'bg-white px-3 py-2 text-base text-gray-900 border-gray-300 sm:text-sm',
            { 'border-red-500': error }
          ]"
          :placeholder="placeholder"
        />

        <!-- Input File -->
        <div v-else-if="elementType === 'file'" class="flex flex-col">
          <label :for="id" class="sr-only">{{ label }}</label>
          <input
            :id="id"
            :name="name"
            type="file"
            accept="image/*"
            @change="handleFileChange"
            class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4"
            :class="{ 'border-red-500': error }"
          />
          <!-- Preview immagine -->
          <div v-if="previewUrl" class="mt-2">
            <img :src="previewUrl" alt="Image Preview" class="max-w-full h-auto rounded-md border border-gray-300" />
          </div>
        </div>

        <!-- Select -->
        <select
          v-else-if="elementType === 'select'"
          :id="id"
          :name="name"
          v-model="internalValue"
          class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          :class="{ 'border-red-500': error }"
        >
          <option v-if="placeholder" :value="null" disabled selected>{{ placeholder }}</option>
          <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>

        <!-- Textarea -->
        <textarea
          v-else-if="elementType === 'textarea'"
          :id="id"
          :name="name"
          v-model="internalValue"
          :placeholder="placeholder"
          rows="4"
          class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          :class="{ 'border-red-500': error }"
        />

        <!-- Checkbox -->
        <template v-else-if="elementType === 'checkbox' && switchStyle">
          <label :for="id" class="flex items-center cursor-pointer relative">
            <input
              :id="id"
              :name="name"
              type="checkbox"
              v-model="internalValue"
              class="sr-only peer"
            />
            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-600 transition-colors duration-300"></div>
            <div class="absolute ml-1 w-4 h-4 bg-white rounded-full shadow-md transform peer-checked:translate-x-5 transition-transform duration-300"></div>
            <span class="ml-3 text-sm font-medium text-gray-900">{{ label }}</span>
          </label>
        </template>
        <template v-else-if="elementType === 'checkbox' && !switchStyle">
          <input
            :id="id"
            :name="name"
            type="checkbox"
            v-model="internalValue"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
            :class="{ 'border-red-500': error }"
          />
          <label :for="id" class="ms-2 text-sm font-medium text-gray-900">
            {{ label }}
          </label>
        </template>

        <!-- Errore (sotto per input, select, file; accanto per checkbox) -->
        <p v-if="error && elementType !== 'checkbox'" class="mt-1 text-sm text-red-600">{{ error }}</p>
        <p v-if="error && elementType === 'checkbox'" class="ms-2 text-sm text-red-600">{{ error }}</p>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      id: { type: String, required: true },
      name: { type: String, required: true },
      label: { type: String, required: true },
      type: { type: String, default: 'text' }, // Usato solo per input
      elementType: { type: String, default: 'input' }, // 'input', 'select', 'checkbox', 'file'
      modelValue: { type: [String, Number, Boolean, File, null], required: false, default: null }, // Aggiunto File e null
      error: { type: String, default: null },
      autocomplete: { type: String, default: null }, // Usato solo per input
      placeholder: { type: String, default: null }, // Usato per input o come opzione vuota in select
      options: { type: Array, default: () => [] }, // Array di { value, label } per select
      switchStyle: { type: Boolean, default: false },
    },
    data() {
      return {
        previewUrl: null, // URL temporaneo per la preview dell'immagine
      };
    },
    computed: {
      internalValue: {
        get() {
          return this.modelValue;
        },
        set(value) {
          this.$emit('update:modelValue', value); // Emettiamo l'evento update:modelValue
        },
      },
    },
    methods: {
      handleFileChange(event) {
        const file = event.target.files[0];
        this.internalValue = file; // Aggiorna il v-model con il file

        // Gestisci la preview
        if (file && file.type.startsWith('image/')) {
          this.previewUrl = URL.createObjectURL(file);
        } else {
          this.previewUrl = null; // Resetta la preview se non è un'immagine
        }
      },
    },
    beforeUnmount() {
      // Pulisci l'URL della preview per evitare memory leak
      if (this.previewUrl) {
        URL.revokeObjectURL(this.previewUrl);
      }
    },
  };
  </script>
