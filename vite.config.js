import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Components from 'unplugin-vue-components/vite';
import path from 'path';
import { createVuetify } from 'vuetify';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        createVuetify(),  // Usa createVuetify() qui
        Components(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    css: {
        preprocessorOptions: {
            sass: {
                additionalData: `@use "vuetify/settings" with (
                    $body-font-family: 'Roboto', sans-serif
                );`
            },
        },
    },
});
