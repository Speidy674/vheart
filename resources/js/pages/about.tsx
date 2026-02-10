import { BetterplaceDonationCard } from '@/components/aboutcard/betterplace-donation-card';
import ClipProcessCard from '@/components/aboutcard/clip-process-card';
import { AboutDonationCard as DonationCard } from '@/components/aboutcard/donation-card';
import HeroCard from '@/components/aboutcard/hero-card';
import SpaceBackground from '@/components/spacebackground';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { home } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function About({
    donationUrl,
    partnerIcon,
}: {
    donationUrl?: string;
    partnerIcon?: string;
}) {
    const { t } = useTranslation('about');

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t('Team'), href: home().url },
    ];

    return (
        <AppHeaderLayout breadcrumbs={breadcrumbs}>
            <Head title={t('page_title')} />

            <div className="relative flex min-h-screen flex-col overflow-hidden bg-blue-50 dark:bg-[#0a0a1a]">
                <SpaceBackground />

                <main className="pwepx-4 relative z-10 flex flex-1 items-center justify-center py-12">
                    <div className="w-full max-w-[1200px] space-y-8">
                        <HeroCard t={t} />
                        <DonationCard
                            t={t}
                            donationUrl={donationUrl}
                            partnerIcon={partnerIcon}
                        />
                        <BetterplaceDonationCard
                            eventId={55712}
                            maxVisibleDonations={8}
                            refreshInterval={300000}
                            projectId={0}
                        />
                        <ClipProcessCard t={t} />
                    </div>
                </main>
            </div>
        </AppHeaderLayout>
    );
}
