import { Card, CardContent } from '@/components/ui/card';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { PublicClip } from '@/types';
import { Head, InfiniteScroll, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

import {
    BestRatedSlider,
} from '@/components/bestRatedSlider';
import { ClipPreview } from '@/components/clip-preview';
import { ClipModal } from '@/components/clipModal';
import SpaceBackground from '@/components/spacebackground';
import YoutubeEmbed from '@/components/embeds/youtube-embed';

type InertiaBaseProps = Record<string, unknown>;
interface PageProps extends InertiaBaseProps {
    bestRated: PublicClip[];
    discover?: {
        data: PublicClip[];
    };
}

export default function Start() {
    const { t } = useTranslation('homepage');
    const { props } = usePage<PageProps>();

    const [openClip, setOpenClip] = useState<PublicClip | null>(null);

    return (
        <AppHeaderLayout>
            <Head title={t('page_title')} />
            <SpaceBackground />
            <div className="relative z-10 mx-auto w-[90vw] py-5">
                <Card className="mx-auto rounded-2xl border border-gray-200 bg-linear-to-br from-white/70 via-white/85 to-white/70 p-8 shadow-2xl ring-1 shadow-black/10 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:bg-none! dark:from-transparent! dark:via-transparent! dark:to-transparent! dark:ring-0 dark:shadow-purple-900/30">
                    <CardContent>
                        <div className="flex flex-col gap-14">
                            <section>
                                <h2 className="mb-4 py-5 text-center text-2xl font-bold">
                                    AKTUELLSTES YOUTUBE VIDEO
                                </h2>

                                <div className="mx-auto aspect-video w-full max-w-4xl overflow-hidden rounded-xl dark:bg-linear-to-b dark:from-white/10 dark:to-black/40 dark:ring-1 dark:ring-white/10">
                                    <YoutubeEmbed url="https://www.youtube-nocookie.com/embed/videoseries?list=UUUefW5IjMaQS_ZFaG4VZi9A" />
                                </div>
                            </section>

                            <BestRatedSlider
                                clips={props.bestRated}
                                headline="AM BESTEN BEWERTET DIESEN MONAT"
                            />

                            <section>
                                <h2 className="mb-4 text-lg font-semibold">
                                    EINGEREICHTE CLIPS
                                </h2>

                                <InfiniteScroll
                                    data="discover"
                                    preserveUrl
                                    buffer={500}
                                >
                                    <div className="grid grid-cols-2 gap-4 pb-0 md:grid-cols-4">
                                        {props.discover?.data?.map((it) => (
                                            <div
                                                key={it.id}
                                                className="overflow-hidden rounded-md transition-transform hover:scale-105"
                                            >
                                                <ClipPreview
                                                    clip={it}
                                                    onClick={() =>
                                                        setOpenClip(it)
                                                    }
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </InfiniteScroll>
                            </section>
                        </div>
                    </CardContent>
                </Card>
            </div>

            {openClip && (
                <ClipModal clip={openClip} onClose={() => setOpenClip(null)} />
            )}
        </AppHeaderLayout>
    );
}
