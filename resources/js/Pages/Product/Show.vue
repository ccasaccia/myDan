<template>
    <div class="p-4 dark:border-gray-700">
        <v-card>
            <v-toolbar color="primary">
                <v-app-bar-nav-icon />

                <v-toolbar-title>{{ pageTitle }}</v-toolbar-title>

                <v-btn icon="mdi-magnify"></v-btn>

                <v-btn icon="mdi-dots-vertical"></v-btn>

                <template v-slot:extension>
                    <v-tabs v-model="model" align-tabs="center">
                        <v-tab
                        v-for="tab in productTab"
                        :key="tab"
                        :value="tab"
                        :text="tab"
                        />
                    </v-tabs>
                </template>
            </v-toolbar>

            <v-tabs-window v-model="model">
                <v-tabs-window-item
                v-for="tab in productTab"
                :key="tab"
                :value="tab"
                >
                    <v-card>
                        <v-card-text>
                            <Detail :data="props.productDetails.original[tab]"/>
                            <!-- {{ props.productDetails.original[tab] }} -->
                        </v-card-text>
                    </v-card>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card>
    </div>
</template>
<script setup>
import { computed, ref, onMounted, defineProps, watch } from 'vue';
import Detail from '@/Components/Detail.vue';

const props = defineProps({
    productDetails: {
        type: Object,
        default: () => ({}),
    },
});
console.log(props.productDetails.original.items);
console.log(props.productDetails.original);

const model = ref(null)
const productTab = ref([])
const pageTitle = computed(() => {
    return (props.productDetails.original.customer.firstname + ' ' + props.productDetails.original.customer.lastname).toUpperCase();
});



console.log('PRODUCT TAB:', productTab);
console.log('PRODUCT TAB:', productTab.value);

for (let index = 0; index < productTab.value.length; index++) {
    console.log(productTab.value[index]);

}



onMounted(() => {
    // goToTab();
});

function goToTab() {
const keysArray = Object.keys(props.productDetails.original).map(key => {
  return props.productDetails.original[key];
});
};


watch(() => props.productDetails.original,
(newValue) => {
    if (newValue) {
    productTab.value = Object.keys(newValue);
    model.value = productTab.value[0];
    }
},
 { immediate: true }
)


</script>
<style></style>
