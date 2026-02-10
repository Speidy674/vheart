import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import './lib/i18n';
import EasterEggModal from '@/components/secret/medium-easteregg';
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

initializeTheme();

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <StrictMode>
                <App {...props} />
                <EasterEggModal
                    triggerWord="cat"
                    imageUrl="https://cataas.com/cat?width=500&height=500&tags=Baby,Cat,ColdCat,Happy,Smol,Sillykitty,Zoomies,babycat,cat%20face,crazy,dummy,cute"
                    creditText="Katzenbilder bereitgestellt von"
                    creditLabel="CATAAS"
                    creditHref="https://cataas.com/"
                />
                <EasterEggModal
                    triggerWord="vid"
                    videoUrl="https://www.youtube-nocookie.com/embed/videoseries?list=UUUefW5IjMaQS_ZFaG4VZi9A"
                    creditText="bereitgestellt von"
                    creditLabel="Youtube"
                    creditHref="https://www.youtube.com/"
                />
            </StrictMode>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});
