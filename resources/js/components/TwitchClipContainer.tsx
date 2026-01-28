import clsx from 'clsx';
import { useEffect, useMemo, useState } from 'react';
import T from '@/components/t';

type TwitchClipProps = {
    slug: string;
    className?: string;
    autoplay?: boolean;
};

export function TwitchClipContainer({
    slug,
    className,
    autoplay = false,
}: TwitchClipProps) {
    const [isLoading, setIsLoading] = useState(true);
    const [hasConsent, setHasConsent] = useState(false);

    useEffect(() => {
        const consent = document.cookie
            .split('; ')
            .find((row) => row.startsWith('twitch-embed-consent='));
        if (consent) {
            // eslint-disable-next-line react-hooks/set-state-in-effect
            setHasConsent(true);
        }
    }, []);

    const handleConsent = () => {
        document.cookie = 'twitch-embed-consent=true; max-age=31536000; path=/';
        setHasConsent(true);
    };

    const clipSrc = useMemo(() => {
        return `https://clips.twitch.tv/embed?clip=${slug}&parent=${document.location.hostname}&autoplay=${autoplay}&muted=false&fullscreen=true`;
    }, [slug, autoplay]);

    if (!hasConsent) {
        return (
            <div
                className={clsx(
                    'relative isolate overflow-hidden bg-black dark:border-black',
                    className,
                )}
            >
                <div className="absolute inset-0 z-20 flex aspect-video flex-col items-center justify-center gap-4 bg-black p-6 text-center">
                    <p className="text-sm font-medium text-zinc-400">
                        <T ns="twitch" k="embeds.consent.text" />
                    </p>
                    <button
                        onClick={handleConsent}
                        className="rounded bg-purple-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-purple-500 hover:text-white"
                    >
                        <T ns="twitch" k="embeds.consent.button" />
                    </button>
                    <a
                        href="https://www.twitch.tv/p/legal/privacy-notice/"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-xs text-zinc-500 underline hover:text-zinc-300"
                    >
                        <T ns="twitch" k="embeds.consent.privacy-policy" />
                    </a>
                </div>
            </div>
        );
    }

    return (
        <div
            className={clsx(
                'relative isolate overflow-hidden bg-black dark:border-black',
                className,
            )}
        >
            <>
                {isLoading && (
                    <div className="absolute inset-0 z-10 flex aspect-video flex-col items-center justify-center gap-3 bg-black text-gray-500">
                        <span className="animate-pulse text-xs font-bold tracking-widest uppercase opacity-75">
                            <T ns="twitch" k="embeds.loading" />
                        </span>
                    </div>
                )}
                <iframe
                    onLoad={() => setIsLoading(false)}
                    src={clipSrc}
                    allow="fullscreen; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    frameBorder="0"
                    allowFullScreen={true}
                    scrolling="no"
                    className={clsx(
                        'aspect-video h-full w-full transition-opacity duration-500',
                        isLoading ? 'opacity-0' : 'opacity-100',
                    )}
                />
            </>
        </div>
    );
}
