import { BetterplaceDonationCard } from '@/components/aboutcard/betterplace-donation-card';
import ClipProcessCard from '@/components/aboutcard/clip-process-card';
import { AboutDonationCard as DonationCard } from '@/components/aboutcard/donation-card';
import HeroCard from '@/components/aboutcard/hero-card';
import Spacebackground from '@/components/spacebackground';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export default function About({
    donationUrl,
    partnerIcon,
}: {
    donationUrl?: string;
    partnerIcon?: string;
}) {
    const { t } = useTranslation('about');

    return (
        <div>
            <Spacebackground/>
            <main className="pwepx-4 relative z-10 flex flex-1 justify-center py-12">
                <div className="w-full max-w-[1200px] space-y-8">
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

                    <HeroCard />
                    <DonationCard
                        donationUrl={donationUrl}
                        partnerIcon={partnerIcon}
                    />
                    <BetterplaceDonationCard />
                    <ClipProcessCard/>
                </div>
            </main>
        </div>
    );
}
