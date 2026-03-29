import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin.css',
                'resources/css/filament/dashboard.css',
                'resources/js/static/app.ts',
                'resources/js/static/alpine.ts',
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
        rolldownOptions: {
            output: {
                manualChunks(id) {
                    if (
                        id.includes('@icons-pack/react-simple-icons') ||
                        id.includes('lucide-react')
                    ) {
                        return 'icons';
                    }

                    return null;
                },
            },
        },
    },
    oxc: {
        jsx: {
            runtime: 'automatic',
        },
    },
});
