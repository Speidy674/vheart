import { lazy, Suspense } from 'react';
const EasterEggModal = lazy(
    () => import('@/components/secret/medium-easteregg'),
);

export default function EasterEggContainer() {
    return (
        <Suspense fallback={null}>
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
        </Suspense>
    );
}
