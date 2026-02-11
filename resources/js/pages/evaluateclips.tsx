import { store } from '@/actions/App/Http/Controllers/ClipVoteController';
import TwitchClipEmbed from '@/components/embeds/twitch-clip-embed';
import ReportButton from '@/components/reports/report-button';
import SpaceBackground from '@/components/spacebackground';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { PublicClip } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import clsx from 'clsx';
import { CircleX, Heart } from 'lucide-react';
import { useEffect } from 'react';
import { useTranslation } from 'react-i18next';

type PageProps = {
    clip: null | PublicClip;
};

export default function EvaluateClips() {
    const { t } = useTranslation('evaluateclips');

    const { props } = usePage<PageProps>();

    const getClip = () => {
        router.reload({ only: ['clip'] });
    };

    useEffect(() => {
        if (!props.clip) {
            getClip();
        }
    }, [props.clip]);

    return (
        <AppHeaderLayout>
            <Head title={t('page_title')} />
            <SpaceBackground />

            <header className="z-1 mb-3 space-y-1 pt-5 text-center sm:mb-4">
                <h1 className="text-base font-bold sm:text-xl 2xl:text-3xl">
                    {t('headline')}
                </h1>
            </header>

            <div className="mx-auto w-full max-w-[60vw] pt-5">
                <div className="relative aspect-video overflow-hidden rounded-xl border bg-background shadow-sm dark:shadow-none dark:ring-1 dark:ring-white/10">
                    <div className="h-full snap-y snap-mandatory overflow-y-auto">
                        {props.clip ? (
                            <section className="flex h-full snap-start snap-always flex-col bg-black">
                                {/* VIDEO */}
                                <div className="relative flex min-h-0 flex-1 items-center justify-center overflow-hidden">
                                    <div className="aspect-video h-full">
                                        <TwitchClipEmbed
                                            slug={props.clip.slug}
                                            className="h-full w-full"
                                        />
                                    </div>
                                </div>

                                {/* Likes */}
                                <div className="absolute top-2 right-2 flex items-center gap-1 rounded-lg bg-black/60 px-2 py-1 text-xs text-white">
                                    <Heart className="h-4 w-4 text-red-500" />
                                    {props.clip.votes ?? 0}
                                </div>

                                {/* ACTION BAR */}
                                <div className="flex shrink-0 items-center justify-center gap-3 py-2 sm:gap-4 sm:py-3">
                                    {/* Like */}
                                    <Link
                                        type="button"
                                        href={store()}
                                        data={{
                                            clip: props.clip.id,
                                            voted: true,
                                        }}
                                        className={clsx(
                                            'grid size-9 place-items-center rounded-full bg-black ring-1 ring-white/10 sm:size-11',
                                            'transition-transform duration-150 ease-out active:scale-95 sm:hover:scale-110',
                                        )}
                                        preserveState
                                        onSuccess={() => {
                                            getClip();
                                        }}
                                    >
                                        <Heart
                                            className={clsx(
                                                'h-4 w-4 sm:h-5 sm:w-5',
                                                'text-white',
                                            )}
                                        />
                                    </Link>

                                    {/* Skip */}
                                    <Link
                                        type="button"
                                        href={store()}
                                        data={{
                                            clip: props.clip.id,
                                            voted: false,
                                        }}
                                        className={clsx(
                                            'grid size-9 place-items-center rounded-full bg-black ring-1 ring-white/10 sm:size-11',
                                            'transition-transform duration-150 ease-out active:scale-95 sm:hover:scale-110',
                                        )}
                                        preserveState
                                        onSuccess={() => {
                                            getClip();
                                        }}
                                    >
                                        <CircleX
                                            className={clsx(
                                                'h-4 w-4 sm:h-5 sm:w-5',
                                                'text-white',
                                            )}
                                        />
                                    </Link>

                                    <ReportButton
                                        items={[
                                            {
                                                type: 'clip',
                                                id: props.clip.id,
                                            },
                                            // TODO: implement broadcaster reportable when using proper types and data
                                        ]}
                                    />
                                </div>
                            </section>
                        ) : (
                            <div className="absolute inset-0 grid place-items-center text-sm text-white/40">
                                {typeof props.clip === 'undefined'
                                    ? ''
                                    : t('nothing_to_vote')}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppHeaderLayout>
    );
}
