import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
          
            input: [
                'zaomicron-frontend/src/main.js',   // точка входа вашего Vue-приложения
                'resources/css/app.css'              // если есть общие стили
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
              '@': path.resolve(__dirname, './zaomicron-frontend/src')
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});