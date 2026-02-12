import AppearanceToggleSlider from '@/components/appearance-slider';
import LanguageToggleDropdown from '@/components/language-slider';
import { Button } from '@headlessui/react';
import {
    lazy,
    ReactNode,
    Suspense,
    useLayoutEffect,
    useMemo,
    useRef,
    useState,
} from 'react';
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


function SocialLink({
    href,
    title,
    colorLight = '#000000',
    colorDark,
    children,
}: {
    href: string;
    title: string;
    colorLight?: string;
    colorDark?: string;
    children: ReactNode;
}) {
    return useMemo(
        () => (
            <a
                href={href}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={title}
                style={{
                    ['--hover-color' as string]: colorLight,
                    ['--hover-color-dark' as string]: colorDark || colorLight,
                }}
                className="text-gray-600 transition-colors hover:text-(--hover-color) dark:text-white/70 dark:hover:text-(--hover-color-dark)"
            >
                {children}
            </a>
        ),
        [children, colorDark, colorLight, href, title],
    );
}

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
                                <li>
                                    <Button className="h-auto px-1.5 py-0.5 text-[10px] text-gray-600 hover:text-gray-900 sm:px-2 sm:py-1 sm:text-xs md:text-sm dark:text-white/70 dark:hover:text-white">
                                        <a href="/faq">{t('faq')}</a>
                                    </Button>
                                </li>
                            </ul>
                        </nav>

                        {/* Social icons */}
                        <div className="min-w-0">
                            <div className="flex items-center justify-center gap-2 sm:gap-3 md:justify-end">
                                <SocialLink
                                    href="https://github.com/VHeart-Clips/VHeart_Webseite"
                                    title="Github"
                                    colorLight="#181717"
                                    colorDark="#F2F5F3"
                                >
                                    <SiGithub className="size-4 sm:size-5" />
                                </SocialLink>

                                <SocialLink
                                    href="https://discord.gg/ThVZHqvXnD"
                                    title="Discord"
                                    colorLight="#5865F2"
                                >
                                    <SiDiscord className="size-4 sm:size-5" />
                                </SocialLink>

                                <SocialLink
                                    href="https://www.youtube.com/@vheartclips"
                                    title="Youtube"
                                    colorLight="#FF0000"
                                >
                                    <SiYoutube className="size-4 sm:size-5" />
                                </SocialLink>

                                <SocialLink
                                    href="https://www.twitch.tv/vheartclips"
                                    title="Youtube"
                                    colorLight="#9146FF"
                                >
                                    <SiTwitch className="size-4 sm:size-5" />
                                </SocialLink>

                                <SocialLink
                                    href="https://x.com/VHeartClips"
                                    title="X (Twitter)"
                                    colorLight="#000000"
                                    colorDark="#FFFFFF"
                                >
                                    <SiX className="size-4 sm:size-5" />
                                </SocialLink>

                                <SocialLink
                                    href="https://www.reddit.com/r/VHeartClips/"
                                    title="Reddit"
                                    colorLight="#FF4500"
                                >
                                    <SiReddit className="size-4 sm:size-5" />
                                </SocialLink>

                                <SocialLink
                                    href="https://bsky.app/profile/vheart.net"
                                    title="Bluesky"
                                    colorLight="#1185FE"
                                >
                                    <SiBluesky className="size-4 sm:size-5" />
                                </SocialLink>

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
