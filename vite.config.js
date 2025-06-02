import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/dashboard.css',
                'resources/js/app.js',
                'resources/js/task-manager.js'
            ],
            refresh: true,
        }),
    ],
    css: {
        postcss: './postcss.config.js',
    },
});
