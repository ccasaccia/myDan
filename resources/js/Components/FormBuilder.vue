<template>
    <div v-bind="$attrs">
        <!-- Toast successo -->
        <transition name="fade">
            <div v-if="successMessage"
                class="relative mb-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow flex items-center gap-2 animate-slide-in">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ successMessage }}</span>
                <button @click="successMessage = null"
                    class="absolute top-1 right-2 text-green-700 text-xl font-bold">&times;</button>
            </div>
        </transition>

        <!-- Toast errore -->
        <transition name="fade">
            <div v-if="errorMessage"
                class="relative mb-4 px-4 py-2 bg-red-100 text-red-800 rounded shadow flex items-center gap-2 animate-slide-in">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>{{ errorMessage }}</span>
                <button @click="errorMessage = null"
                    class="absolute top-1 right-2 text-red-700 text-xl font-bold">&times;</button>
            </div>
        </transition>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form @submit.prevent="handleSubmit">
                <div class="flex flex-wrap -mx-2">
                    <div v-for="field in filteredFields" :key="field.name" :class="[
                        (field.elementType === 'checkbox' && field.switchStyle)
                            ? 'w-full flex flex-col items-center justify-center mb-6'
                            : 'px-2 mb-4 ' + (
                                field.row === 1
                                    ? 'w-full'
                                    : field.row === 3
                                        ? 'w-full md:w-1/3'
                                        : field.row === 4
                                            ? 'w-full md:w-1/4'
                                            : 'w-full md:w-1/2'
                            )
                    ]">
                        <template v-if="field.elementType === 'checkbox' && field.switchStyle">
                            <span v-if="field.switchLabel" class="block mb-2 text-gray-700 font-medium text-sm">
                                {{ field.switchLabel }}
                            </span>
                        </template>

                        <InputField :id="field.name" :name="field.name"
                            :label="field.elementType === 'checkbox' && field.switchStyle ? '' : field.label"
                            :type="field.type || 'text'" :elementType="field.elementType || 'input'"
                            v-model="form[field.name]" :options="field.options || []" :error="errors[field.name]"
                            :placeholder="field.placeholder || ''" :switchStyle="field.switchStyle || false" />
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        :disabled="loading">
                        <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        {{ loading ? 'Salvando...' : 'Salva' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import InputField from '@/Components/InputField.vue';
import { router } from '@inertiajs/vue3';

export default {
    inheritAttrs: false,
    components: {
        InputField,
    },
    props: {
        fields: {
            type: Array,
            required: true,
        },
        errors: {
            type: Object,
            default: () => ({}),
        },
        submitUrl: {
            type: String,
            required: true,
        },
        method: {
            type: String,
            default: 'post',
        },
        externalData: {
            type: Object,
            default: () => ({})
        },
    },
    data() {
        const form = {};
        this.fields.forEach(field => {
            form[field.name] = field.default ?? (field.elementType === 'checkbox' ? false : '');
        });
        form.is_active = form.is_active ?? true;
        return {
            form,
            loading: false,
            errorMessage: null,
            successMessage: null,
        };
    },
    computed: {
        filteredFields() {
            return this.fields.filter(field => field.name !== 'is_active');
        },
    },
    methods: {
        handleSubmit() {
            this.loading = true;
            this.errorMessage = null;
            this.successMessage = null;
            router.visit(this.submitUrl, {
                method: this.method,
                data: this.form,
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    this.successMessage = 'Cantiere salvato con successo!';
                    setTimeout(() => this.successMessage = null, 3000);
                },
                onError: (errors) => {
                    this.errorMessage = 'Errore durante il salvataggio. Controlla i dati.';
                    this.loading = false;
                },
                onFinish: () => {
                    this.loading = false;
                },
            });
        },
    },
};
</script>

<style>
@keyframes slide-in {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out forwards;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
