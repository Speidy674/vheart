import GenericEmbed from '@/components/embeds/generic-embed';
import T from '@/components/t';
import { useMemo } from 'react';

export interface TwitchClipEmbedProps {
    slug: string;
    thumbnail?: string;
    autoplay?: boolean;
    parent?: string;
    className?: string;
}
export default function TwitchClipEmbed({
    slug,
    thumbnail,
    autoplay,
    parent,
    className,
}: TwitchClipEmbedProps) {
    const clipSrc = useMemo(() => {
        return `https://clips.twitch.tv/embed?clip=${slug}&parent=${parent || document?.location?.hostname || 'localhost'}&autoplay=${autoplay ? 'true' : 'false'}&muted=false&fullscreen=true`;
    }, [slug, parent, autoplay]);

    return (
        <GenericEmbed
            url={clipSrc}
            cookieName="twitch_embed_consent"
            className={className}
        >
            {({ accept }) => (
                <div className="relative flex h-full flex-col items-center justify-center overflow-hidden bg-zinc-900 p-6 text-center text-white">
                    {thumbnail && (
                        <img
                            src={thumbnail}
                            alt="Clip Thumbnail"
                            loading="lazy"
                            decoding="async"
                            className="absolute inset-0 z-0 h-full w-full scale-110 object-cover brightness-25 blur-md"
                        />
                    )}
                    <div className="relative z-10 flex flex-col items-center space-y-4 drop-shadow-md">
                        <p className="text-base font-medium text-balance text-zinc-100">
                            <T ns="embeds" k="twitch.consent.text" />
                        </p>
                        <button
                            onClick={accept}
                            className="text-md rounded bg-purple-600 px-4 py-2 font-bold text-white transition hover:bg-purple-500 hover:text-white"
                        >
                            <T ns="embeds" k="twitch.consent.button" />
                        </button>
                        <a
                            href="https://www.twitch.tv/p/legal/privacy-notice/"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="text-md text-zinc-300 underline hover:text-zinc-100"
                        >
                            <T ns="embeds" k="twitch.consent.privacy-policy" />
                        </a>
                    </div>
                </div>
            )}
        </GenericEmbed>
    );
}
