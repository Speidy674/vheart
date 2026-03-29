import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.ts',
                'resources/js/alpine.ts',
                'resources/css/filament/admin.css',
                'resources/css/filament/dashboard.css',
            ],
            assets: [
                'resources/images/**',
                'resources/fonts/**/*.(woff2|woff|ttf)',
            ],
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
    ],
    build: {
        target: 'baseline-widely-available',
    },
    oxc: {
        jsx: {
            runtime: 'automatic',
        },
    },
});
