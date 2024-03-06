import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/us/index.sass', 'resources/js/us/index.js'],
            refresh: true,
        }),
    ],
});
