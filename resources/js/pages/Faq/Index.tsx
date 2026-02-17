import SpaceBackground from '@/components/spacebackground';
import { Card } from '@/components/ui/card';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { FaqEntryResource, SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

const FaqItem = ({ item }: { item: FaqEntryResource }) => (
    <details
        name="faq-accordion"
        className="group rounded-xl border border-gray-400 bg-white/80 shadow-2xl ring-1 shadow-black/10 ring-black/5 transition-all duration-200 dark:border-gray-600 dark:bg-black/30 dark:ring-0 dark:shadow-purple-900/30"
    >
        <summary className="relative flex cursor-pointer list-none items-center justify-between px-5 py-4 focus:outline-none">
            <span className="absolute left-5 font-bold text-purple-600 dark:text-purple-400">
                Q
            </span>
            <span className="flex-1 px-8 text-center text-base font-medium text-gray-900 dark:text-white">
                {item.title}
            </span>
            <span className="absolute right-5 transform text-gray-600 transition-transform duration-200 group-open:rotate-180 group-open:text-purple-600 dark:text-gray-400 dark:group-open:text-purple-400">
                ▼
            </span>
        </summary>
        <div className="rounded-b-xl border-t border-gray-200 bg-white/50 px-5 py-4 dark:border-white/20 dark:bg-white/5">
            <div className="flex gap-3">
                <span className="font-bold text-emerald-600 dark:text-emerald-400">
                    A
                </span>
                {/* the HTML we get from backend is parsed Markdown and stripped from unsafe things */}
                <p
                    className="flex-1 text-center whitespace-pre-line text-gray-700 dark:text-gray-300"
                    dangerouslySetInnerHTML={{ __html: item.body }}
                ></p>
            </div>
        </div>
    </details>
);

export default function Index() {
    const { t } = useTranslation('faq');
    const { props } = usePage<SharedData>();
    const faq = (props.faq as FaqEntryResource[]) ?? [];

    return (
        <AppHeaderLayout>
            <Head title={'FAQ'} />

            <div className="relative overflow-hidden">
                <SpaceBackground />

                <main className="relative z-10 mx-auto w-full max-w-[900px] space-y-8 px-4 py-12">
                    <Card className="rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-6 shadow-2xl ring-1 shadow-black/10 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:ring-0 dark:shadow-purple-900/30">
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
                                {faq.map((item) => (
                                    <FaqItem key={item.id} item={item} />
                                ))}
                            </div>
                        )}
                    </Card>
                </main>
            </div>
        </AppHeaderLayout>
    );
}
