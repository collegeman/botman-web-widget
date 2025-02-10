import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
          input: [
            'resources/css/beacon.css',
            'resources/js/chat.js',
            'resources/js/beacon.js',
            'resources/js/widget.js',
          ],
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
    ],
});
