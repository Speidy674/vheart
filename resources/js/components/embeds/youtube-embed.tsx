import GenericEmbed from '@/components/embeds/generic-embed';
import T from '@/components/t';

export interface YoutubeEmbedProps {
    url: string;
    className?: string;
}
export default function YoutubeEmbed({
    url,
    className,
}: YoutubeEmbedProps) {
    return (
        <GenericEmbed
            url={url}
            cookieName="youtube_embed_consent"
            className={className}
        >
            {({ accept }) => (
                <div className="flex h-full flex-col items-center justify-center space-y-4 p-6 text-center text-white">
                    <p className="text-base font-medium text-zinc-400 text-balance">
                        <T ns="embeds" k="youtube.consent.text" />
                    </p>
                    <button
                        onClick={accept}
                        className="rounded bg-red-600 px-4 py-2 text-md font-bold text-white transition hover:bg-red-500 hover:text-white"
                    >
                        <T ns="embeds" k="youtube.consent.button" />
                    </button>
                    <a
                        href="https://www.youtube.com/howyoutubeworks/privacy/"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-md text-zinc-500 underline hover:text-zinc-300"
                    >
                        <T ns="embeds" k="youtube.consent.privacy-policy" />
                    </a>
                </div>
            )}
        </GenericEmbed>
    );
}
