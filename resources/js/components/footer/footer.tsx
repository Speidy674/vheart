import AppearanceToggleSlider from '@/components/appearance-slider';
import LanguageToggleDropdown from '@/components/language-slider';
import { Button } from '@headlessui/react';
import { lazy, Suspense, useLayoutEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import {
    SiBluesky,
    SiDiscord,
    SiGithub,
    SiReddit,
    SiTwitch,
    SiX,
    SiYoutube,
} from '@icons-pack/react-simple-icons';
const EasterEggContainer = lazy(
    () => import('@/components/easer-egg-container'),
);


export default function Footer() {
    const { t } = useTranslation('footer');
    const footerRef = useRef<HTMLElement>(null);
    const [footerHeight, setFooterHeight] = useState(0);

    useLayoutEffect(() => {
        const updateFooterHeight = () => {
            if (footerRef.current) {
                const height = footerRef.current.offsetHeight;
                setFooterHeight(height);
                document.documentElement.style.setProperty(
                    '--footer-height',
                    `${height}px`,
                );
            }
        };

        updateFooterHeight();

        const resizeObserver = new ResizeObserver(updateFooterHeight);
        if (footerRef.current) {
            resizeObserver.observe(footerRef.current);
        }

        return () => {
            resizeObserver.disconnect();
        };
    }, [footerRef]);

    return (
        <>
            <Suspense fallback={null}>
                <EasterEggContainer />
            </Suspense>
            <footer
                ref={footerRef}
                className="fixed right-0 bottom-0 left-0 z-40 border-t border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 py-2 text-gray-900 ring-black/5 sm:py-4 md:py-6 dark:border-white/20 dark:bg-black/80 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent dark:text-white/85 dark:ring-0"
            >
                <div className="container mx-auto px-2 sm:px-4">
                    <div className="grid items-center gap-0.5 sm:gap-2 md:grid-cols-3 md:gap-4">
                        {/* Copyright */}
                        <div className="min-w-0 text-center text-[10px] leading-tight sm:text-xs md:text-left md:text-sm">
                            © {new Date().getFullYear()} VHeart.{' '}
                            {t('all_rights_reserved')}
                        </div>

                        {/* Links */}
                        <nav
                            aria-label={t('footer_navigation')}
                            className="min-w-0"
                        >
                            <ul className="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 md:gap-3">
                                <li>
                                    <Button className="h-auto px-1.5 py-0.5 text-[10px] text-gray-600 hover:text-gray-900 sm:px-2 sm:py-1 sm:text-xs md:text-sm dark:text-white/70 dark:hover:text-white">
                                        <a href="/privacy">
                                            {t('privacy.footer')}
                                        </a>
                                    </Button>
                                </li>
                                <li>
                                    <Button className="h-auto px-1.5 py-0.5 text-[10px] text-gray-600 hover:text-gray-900 sm:px-2 sm:py-1 sm:text-xs md:text-sm dark:text-white/70 dark:hover:text-white">
                                        <a href="/imprint">
                                            {t('imprint.footer')}
                                        </a>
                                    </Button>
                                </li>
                                <li>
                                    <Button className="h-auto px-1.5 py-0.5 text-[10px] text-gray-600 hover:text-gray-900 sm:px-2 sm:py-1 sm:text-xs md:text-sm dark:text-white/70 dark:hover:text-white">
                                        <a href="/team">{t('team')}</a>
                                    </Button>
                                </li>
                                <li>
                                    <Button className="h-auto px-1.5 py-0.5 text-[10px] text-gray-600 hover:text-gray-900 sm:px-2 sm:py-1 sm:text-xs md:text-sm dark:text-white/70 dark:hover:text-white">
                                        <a href="/about-us">{t('about')}</a>
                                    </Button>
                                </li>
                            </ul>
                        </nav>

                        {/* Social icons */}
                        <div className="min-w-0">
                            <div className="flex items-center justify-center gap-2 sm:gap-3 md:justify-end">
                                <a
                                    href="https://github.com/VHeart-Clips/VHeart_Webseite"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label={t('github_aria', 'GitHub')}
                                    className="text-gray-600 transition-colors hover:text-[#181717] dark:text-white/70 dark:hover:text-[#F2F5F3]"
                                >
                                    <SiGithub className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <a
                                    href="https://discord.gg/ThVZHqvXnD"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label={t('discord_aria', 'Discord')}
                                    className="text-gray-600 transition-colors hover:text-[#5865F2] dark:text-white/70 dark:hover:text-[#5865F2]"
                                >
                                    <SiDiscord className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <a
                                    href="https://www.youtube.com/@vheartclips"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="YouTube"
                                    className="text-gray-600 transition-colors hover:text-[#FF0000] dark:text-white/70 dark:hover:text-[#FF0000]"
                                >
                                    <SiYoutube className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <a
                                    href="https://www.twitch.tv/vheartclips"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="Twitch"
                                    className="text-gray-600 transition-colors hover:text-[#9146FF] dark:text-white/70 dark:hover:text-[#9146FF]"
                                >
                                    <SiTwitch className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <a
                                    href="https://x.com/VHeartClips"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="X (Twitter)"
                                    className="text-gray-600 transition-colors hover:text-[#000000] dark:text-white/70 dark:hover:text-[#FFFFFF]"
                                >
                                    <SiX className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <a
                                    href="https://www.reddit.com/r/VHeartClips/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="Reddit"
                                    className="text-gray-600 transition-colors hover:text-[#FF4500] dark:text-white/70 dark:hover:text-[#FF4500]"
                                >
                                    <SiReddit className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <a
                                    href="https://bsky.app/profile/vheart.net"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="Bluesky"
                                    className="text-gray-600 transition-colors hover:text-[#1185FE] dark:text-white/70 dark:hover:text-[#1185FE]"
                                >
                                    <SiBluesky className="h-4 w-4 sm:h-5 sm:w-5" />
                                </a>

                                <div className="h-4 w-px bg-gray-200 dark:bg-white/20" />

                                <div className="flex items-center gap-1">
                                    <LanguageToggleDropdown />
                                    <AppearanceToggleSlider />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            <div
                style={{ height: `${footerHeight}px` }}
                className="pointer-events-none opacity-0"
                aria-hidden="true"
            />
        </>
    );
}
