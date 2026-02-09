import SpaceBackground from '@/components/spacebackground';
import { Card } from '@/components/ui/card';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { FaqEntryResource, SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-react';

export default function Index() {
    const { t } = useTranslation('faq');
    const { props } = usePage<SharedData>();
    // eslint-disable-next-line react-hooks/exhaustive-deps
    const faq = (props.faq as FaqEntryResource[]) ?? [];

    const [openId, setOpenId] = useState<number | null>(faq?.[0]?.id ?? null);

    useEffect(() => {
        if (openId == null) return;
        if (!faq.some((x) => x.id === openId)) setOpenId(null);
    }, [faq, openId]);

    return (
        <AppHeaderLayout>
            <Head title={'FAQ'} />

            <div className="relative min-h-screen overflow-hidden bg-blue-50 dark:bg-[#0a0a1a]">
                <SpaceBackground />

                <main className="space-y-8 relative z-10 mx-auto w-full max-w-[900px] px-4 py-12">
                    <div>
                        <Button
                            size="lg"
                            onClick={() => window.history.back()}
                            className="rounded-full border-0 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400 px-8 py-5 font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-emerald-600 hover:via-teal-500 hover:to-cyan-500 hover:shadow-xl hover:shadow-emerald-500/25"
                        >
                            <ArrowLeft className="h-4 w-4" />
                            {t('back')}
                        </Button>
                    </div>
                    <Card className="rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-6 shadow-2xl ring-1 shadow-black/10 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent dark:ring-0 dark:shadow-purple-900/30">
                        <div className="mb-8 flex flex-col items-center text-center">
                            <h1 className="bg-gradient-to-r from-purple-700 via-gray-900 to-cyan-700 bg-clip-text text-3xl font-bold text-transparent dark:from-purple-300 dark:via-white dark:to-cyan-300">
                                {t('title')}
                            </h1>
                            <p className="mt-2 max-w-xl text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                                {t('desc')}
                            </p>
                        </div>

                        {faq.length === 0 ? (
                            <div className="rounded-xl border border-dashed border-gray-200 bg-white/50 p-10 text-center text-gray-600 dark:border-white/15 dark:bg-white/5 dark:text-gray-300">
                                Noch keine FAQ-Einträge vorhanden.
                            </div>
                        ) : (
                            <div className="space-y-2">
                                {faq.map((item) => {
                                    const isOpen = openId === item.id;

                                    return (
                                        <div
                                            key={`faq-${item.id}`}
                                            className={`rounded-xl border border-gray-400 bg-white/80 shadow-2xl ring-1 shadow-black/10 ring-black/5 transition-all duration-200 dark:border-gray-600 dark:bg-black/30 dark:ring-0 dark:shadow-purple-900/30`}
                                        >
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    setOpenId((prev) =>
                                                        prev === item.id
                                                            ? null
                                                            : item.id,
                                                    )
                                                }
                                                className="relative flex w-full items-center justify-between px-5 py-4 text-center"
                                                aria-expanded={isOpen}
                                                aria-controls={`faq-panel-${item.id}`}
                                            >
                                                <span className="absolute top-1/2 left-5 -translate-y-1/2 font-bold text-purple-600 dark:text-purple-400">
                                                    Q
                                                </span>
                                                <span className="flex-1 px-8 text-base font-medium text-gray-900 dark:text-white">
                                                    {item.title}
                                                </span>
                                                <span
                                                    className={`absolute top-1/2 right-5 -translate-y-1/2 transform transition-all duration-200 ${
                                                        isOpen
                                                            ? 'rotate-180 text-purple-600 dark:text-purple-400'
                                                            : 'text-gray-600 dark:text-gray-400'
                                                    } `}
                                                    aria-hidden="true"
                                                >
                                                    ▼
                                                </span>
                                            </button>

                                            <div
                                                id={`faq-panel-${item.id}`}
                                                className={`grid overflow-hidden transition-all duration-200 ${
                                                    isOpen
                                                        ? 'grid-rows-[1fr] opacity-100'
                                                        : 'grid-rows-[0fr] opacity-0'
                                                } `}
                                            >
                                                <div className="overflow-hidden">
                                                    <div className="rounded-b-xl border-t border-gray-200 bg-white/50 px-5 py-4 dark:border-white/20 dark:bg-white/5">
                                                        <div className="flex gap-3">
                                                            <span className="font-bold text-emerald-600 dark:text-emerald-400">
                                                                A
                                                            </span>
                                                            <p className="flex-1 text-sm whitespace-pre-line text-gray-700 dark:text-gray-300">
                                                                {item.body}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </Card>
                </main>
            </div>
        </AppHeaderLayout>
    );
}
