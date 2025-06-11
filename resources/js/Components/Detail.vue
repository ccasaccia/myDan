<template>
    <div>
        <v-list lines="three">
            <v-list-item v-for="item in userControls" :key="item.value"
                :title="(item.key + ': ' + item.value).toUpperCase()"></v-list-item>
        </v-list>
    </div>
</template>
<script setup>
import { ref, defineProps, computed } from 'vue'

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});

//   const userControls = computed(() => {
//     return Object.entries(props.data).map(([key, value]) => ({
//       key,
//       value: typeof value === 'object' && value !== null ? JSON.stringify(value, null, 2) : value.toString()
//     }));
//   });
const userControls = computed(() => {
    return Object.entries(props.data).map(([key, value]) => {
        //controlliamo se è un oggetto
        if (typeof value === 'object' && value !== null) {
            //verifichiamo se a sua volta è un array
            if (Array.isArray(value)) {
                const elements = [];
                value.forEach((element, index) => {
                    elements.push(`${key}[${index}]: ${JSON.stringify(element)}`);
                });
                return {
                    key,
                    value: elements.join(', ')
                };
            } else {
                // For non-array objects, stringify the object
                return {
                    key,
                    value: JSON.stringify(value, null, 2)
                };
            }
        } else {
            return {
                key,
                value: value !== null && value !== undefined ? value.toString() : ''
            };
        }
    });
});

</script>
<style></style>
