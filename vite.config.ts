import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import laravel from 'laravel-vite-plugin';
import path from 'node:path';
import laravelTranslations from 'vite-plugin-laravel-translations';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/ts/app.ts'],
            refresh: true,
        }),
        laravelTranslations["default"]/* 'laravelTranslations is not a function' type-safe hack */({
            namespace: "server"
        }),
        svelte()
    ],
    resolve: {
        alias: {
            $lib: path.resolve("./resources/ts/lib")
        }
    }
})
