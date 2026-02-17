import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig, PluginOption } from 'vite';
import { exec } from 'node:child_process';

// update language files in real time on dev
const i18nHotReload = () => ({
    name: 'i18n-hot-reload',
    apply: 'serve',
    buildStart() {
        exec('php artisan translations:export', (error) => {
            if (error) console.error(`i18n initial export failed: ${error}`);
        });
    },
    handleHotUpdate({ file }) {
        if (file.includes('/lang/') && file.endsWith('.php')) {
            console.log(`language file changed: ${file}`);

            exec('php artisan translations:export', (error) => {
                if (error) {
                    console.error(`i18n export failed: ${error}`);
                    return;
                }
            });
        }
    },
} satisfies PluginOption);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.tsx',
                'resources/css/filament/admin.css',
            ],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react({
            babel: {
                plugins: ['babel-plugin-react-compiler'],
            },
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        i18nHotReload(),
    ],
    build: {
        rollupOptions: {
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
    esbuild: {
        jsx: 'automatic',
    },
    build: {
        target: 'baseline-widely-available',
    },
});
