import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Heart } from 'lucide-react';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

const EVENT_ID = 55712;
const TOTAL_DONATIONS_LIMIT = 20;
const REFRESH_INTERVAL = 300000;

interface Donation {
    author?: {
        name: string;
        picture?: {
            links?: Array<{ href: string }>;
        };
    };
    donator_name?: string;
    donator_picture?: string;
    donated_amount_in_cents?: number;
    amount_in_cents?: number;
    message?: string;
    created_at?: string;
    donated_at?: string;
    inserted_at?: string;
    updated_at?: string;
}

interface EventData {
    title: string;
    description?: string;
    donated_amount_in_cents: number;
}

function pickFirstString(...values: Array<string | undefined | null>): string {
    for (const v of values) {
        if (typeof v === 'string' && v.trim().length > 0) return v;
    }
    return '';
}

export function BetterplaceDonationCard() {
    const { t } = useTranslation('betterplace');
    const [eventData, setEventData] = useState<EventData | null>(null);
    const [donations, setDonations] = useState<Donation[]>([]);
    const [loadingProject, setLoadingProject] = useState(true);
    const [loadingDonations, setLoadingDonations] = useState(true);
    const [error, setError] = useState<string>('');

    const formatCurrency = (amount: number) =>
        amount.toLocaleString('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

    const loadData = async (signal?: AbortSignal) => {
        try {
            const [eventRes, donationsRes] = await Promise.all([
                fetch(
                    `https://api.betterplace.org/de/api_v4/fundraising_events/${EVENT_ID}.json`,
                    { signal },
                ),
                fetch(
                    `https://api.betterplace.org/de/api_v4/fundraising_events/${EVENT_ID}/opinions.json`,
                    { signal },
                ),
            ]);

            if (!eventRes.ok || !donationsRes.ok) throw new Error('API-Fehler');

            const event: EventData = await eventRes.json();
            const donationsData = await donationsRes.json();

            setEventData(event);
            setDonations(
                donationsData.data?.slice(0, TOTAL_DONATIONS_LIMIT) || [],
            );
            setError('');
        } catch (err) {
            if (err instanceof DOMException && err.name === 'AbortError')
                return;
            setError(
                'Spenden konnten nicht geladen werden. Bitte versuche es später erneut.',
            );
        } finally {
            setLoadingProject(false);
            setLoadingDonations(false);
        }
    };

    useEffect(() => {
        const controller = new AbortController();
        loadData(controller.signal);

        const interval = setInterval(() => {
            const c = new AbortController();
            loadData(c.signal);
        }, REFRESH_INTERVAL);

        return () => {
            controller.abort();
            clearInterval(interval);
        };
    }, []);

    return (
        <section className="w-full px-4 py-8">
            <div className="mx-auto grid max-w-7xl grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-12">
                <Card className="flex w-full flex-col rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-6 shadow-xl ring-1 shadow-black/10 ring-black/5 md:p-8 lg:h-[55rem] dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:ring-0 dark:shadow-purple-900/30">
                    <div className="custom-scrollbar overflow-y-auto pr-2">
                        {loadingProject ? (
                            <div className="flex items-center justify-center gap-3 py-12">
                                <div className="h-6 w-6 animate-spin rounded-full border-2 border-gray-900 border-t-transparent dark:border-white dark:border-t-transparent" />
                                <span className="text-gray-800 dark:text-white/90">
                                    Lade Projekt…
                                </span>
                            </div>
                        ) : (
                            <div className="space-y-6">
                                <h2 className="bg-gradient-to-r from-purple-700 via-gray-900 to-cyan-700 bg-clip-text text-2xl font-bold text-transparent sm:text-3xl dark:from-purple-300 dark:via-white dark:to-cyan-300">
                                    {eventData?.title || 'Spendenprojekt'}
                                </h2>

                                {eventData?.description && (
                                    <div className="text-sm leading-relaxed text-gray-800 md:text-base dark:text-white/90">
                                        <div
                                            dangerouslySetInnerHTML={{
                                                __html: eventData.description,
                                            }}
                                            className="[&_a]:text-[#8ea0ff] [&_a]:underline [&_a]:decoration-1 [&_a]:underline-offset-2 [&_a]:opacity-95 [&_a:hover]:text-gray-900 [&_a:hover]:opacity-100 dark:[&_a:hover]:text-white dark:[&_a:hover]:opacity-100"
                                        />
                                    </div>
                                )}

                                <div className="rounded-xl border border-gray-300/80 bg-white/60 p-6 dark:border-white/15 dark:bg-black/20">
                                    <div className="mb-4 flex items-center justify-center gap-2 text-xs font-medium tracking-wider uppercase sm:text-sm">
                                        <span className="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                        <span className="text-gray-800 dark:text-white/90">
                                            Gesamtsumme
                                        </span>
                                        <span className="h-1.5 w-1.5 rounded-full bg-cyan-500" />
                                    </div>
                                    <div className="bg-gradient-to-r from-purple-700 via-gray-900 to-cyan-700 bg-clip-text text-center text-3xl font-bold text-transparent sm:text-4xl md:text-5xl dark:from-purple-300 dark:via-white dark:to-cyan-300">
                                        {formatCurrency(
                                            (eventData?.donated_amount_in_cents ||
                                                0) / 100,
                                        )}{' '}
                                        €
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </Card>

                <Card className="flex w-full flex-col rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-6 shadow-xl ring-1 shadow-black/10 ring-black/5 md:p-8 lg:h-[55rem] dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:ring-0 dark:shadow-purple-900/30">
                    <div className="flex h-full flex-col">
                        <h3 className="mb-6 bg-gradient-to-r from-purple-700 via-gray-900 to-cyan-700 bg-clip-text text-xl font-bold text-transparent sm:text-2xl dark:from-purple-300 dark:via-white dark:to-cyan-300">
                            {t('last_donations')}
                        </h3>

                        {loadingDonations ? (
                            <div className="flex flex-1 items-center justify-center gap-3">
                                <div className="h-6 w-6 animate-spin rounded-full border-2 border-gray-900 border-t-transparent dark:border-white dark:border-t-transparent" />
                                <span className="text-gray-800 dark:text-white/90">
                                    Lade Spenden…
                                </span>
                            </div>
                        ) : error ? (
                            <div className="rounded-xl border border-red-300/80 bg-red-50/60 p-4 dark:border-red-900/30 dark:bg-red-900/20">
                                <p className="text-sm text-red-800 dark:text-red-200">
                                    {error}
                                </p>
                            </div>
                        ) : donations.length === 0 ? (
                            <div className="flex flex-1 items-center justify-center">
                                <p className="text-gray-800 dark:text-white/90">
                                    {t('no_donations_yet')}
                                </p>
                            </div>
                        ) : (
                            <div className="flex-1 overflow-hidden">
                                <style>{`
                                    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                                    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
                                    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.2); border-radius: 10px; }
                                    .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
                                    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); }
                                `}</style>

                                <div className="custom-scrollbar h-full max-h-[30rem] overflow-y-auto pr-2 lg:max-h-none">
                                    {donations.map((donation, index) => {
                                        const amount =
                                            (donation.donated_amount_in_cents ||
                                                donation.amount_in_cents ||
                                                0) / 100;

                                        const name =
                                            pickFirstString(
                                                donation.author?.name,
                                                donation.donator_name,
                                            ) || 'Anonym';

                                        const image = pickFirstString(
                                            donation.author?.picture?.links?.[0]
                                                ?.href,
                                            donation.donator_picture,
                                        );

                                        const message = pickFirstString(
                                            donation.message?.trim(),
                                        );

                                        const hasMessage = message.length > 0;

                                        return (
                                            <div
                                                key={index}
                                                className="border-b border-gray-300/80 py-4 last:border-b-0 dark:border-white/15"
                                            >
                                                <div className="flex items-start gap-4">
                                                    <div className="w-20 shrink-0 bg-gradient-to-r from-purple-700 to-cyan-700 bg-clip-text text-base font-bold text-transparent dark:from-purple-300 dark:to-cyan-300">
                                                        {formatCurrency(amount)}{' '}
                                                        €
                                                    </div>

                                                    <div className="shrink-0">
                                                        {image ? (
                                                            <img
                                                                src={image}
                                                                alt={name}
                                                                className="h-9 w-9 rounded-full border border-gray-300/80 object-cover dark:border-white/20"
                                                            />
                                                        ) : (
                                                            <div className="h-9 w-9 rounded-full bg-emerald-400/80" />
                                                        )}
                                                    </div>

                                                    <div className="min-w-0 flex-1">
                                                        <div className="truncate text-sm font-semibold text-gray-900 dark:text-white/90">
                                                            {name}
                                                        </div>
                                                        {hasMessage && (
                                                            <div className="mt-1 line-clamp-3 text-xs break-words text-[#8ea0ff]">
                                                                {message}
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        )}

                        <div className="border-t border-gray-300/80 pt-4 sm:pt-6 dark:border-white/15">
                            <div className="flex justify-center">
                                <a
                                    href="https://secure.betterplace.org/de/donate/platform/fundraising-events/55712"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="w-full sm:w-auto"
                                >
                                    <Button
                                        size="lg"
                                        className="w-full rounded-full border-0 bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-400 px-6 py-4 text-sm font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-emerald-600 hover:via-teal-500 hover:to-cyan-500 hover:shadow-xl hover:shadow-emerald-500/25 sm:px-8 sm:py-5 sm:text-base"
                                    >
                                        <Heart className="mr-2 h-4 w-4 sm:h-5 sm:w-5" />
                                        {t('donate_now')}
                                    </Button>
                                </a>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </section>
    );
}
