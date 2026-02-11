import { cn } from '@/lib/utils';
import { LucideInfo, LucideLoaderCircle } from 'lucide-react';
import React, {
    memo,
    ReactNode,
    useEffect,
    useRef,
    useState,
} from 'react';
import { useCookie } from '@/hooks/useCookie';
import T from '@/components/t';

export interface GenericEmbedProps {
    /* Embed Url */
    url: string;
    /* Link for manual Visits */
    link?: string;
    /* Will ask for consent before displaying content if set, using the cookie to remember */
    cookieName?: string;
    title?: string;
    className?: string;
    children?: (props: { accept: () => void }) => ReactNode;
}

const GenericEmbed = memo(
    ({
        url,
        link,
        title = 'Embedded Content',
        className,
        cookieName,
        children,
    }: GenericEmbedProps) => {
        const [cookieValue, setCookie] = useCookie(cookieName || '');
        const [isLoading, setIsLoading] = useState(true);
        const [hasConsentGiven, setHasConsentGiven] = useState(false);
        const iframeRef = useRef<HTMLIFrameElement>(null);

        const isConsentRequired = !!cookieName;
        const hasConsent =
            !isConsentRequired || !!cookieValue || hasConsentGiven;

        useEffect(() => {
            const setLoading = async () => setIsLoading(true);
            void setLoading();
        }, [url]);

        const accept = () => {
            setHasConsentGiven(true);
            setCookie('1', { days: 30 });
        };

        if (!url) {
            return <GenericEmbedShell className={className} />;
        }

        try {
            new URL(url);
        } catch {
            return (
                <GenericEmbedShell className={className}>
                    <GenericEmbedInvalidContentMessage />
                </GenericEmbedShell>
            );
        }

        return (
            <GenericEmbedShell className={className}>
                {hasConsent ? (
                    <>
                        {isLoading && <GenericEmbedLoadingOverlay />}

                        <iframe
                            ref={iframeRef}
                            src={url}
                            title={title}
                            onLoad={() => setIsLoading(false)}
                            className={cn(
                                'h-full w-full border-0 transition-opacity duration-500',
                                isLoading ? 'opacity-0' : 'opacity-100',
                            )}
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                            allowFullScreen
                            loading="lazy"
                        />
                    </>
                ) : (
                    <div className="absolute inset-0 z-20 h-full w-full bg-black">
                        {children ? (
                            children({ accept })
                        ) : (
                            <GenericEmbedConsentRequest url={url} link={link} accept={accept} />
                        )}
                    </div>
                )}
            </GenericEmbedShell>
        );
    },
    (prev, next) => {
        // only render on cookie name or url change
        return prev.url === next.url && prev.cookieName === next.cookieName;
    },
);

export default GenericEmbed;

export const GenericEmbedShell = ({
    className,
    children,
}: {
    className?: string;
    children?: ReactNode;
}) => (
    <div
        className={cn(
            'relative isolate overflow-hidden',
            className || 'aspect-video rounded-lg bg-black dark:border-black',
        )}
    >
        {children}
    </div>
);

export const GenericEmbedLoadingOverlay = () => (
    <div className="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black text-gray-500">
        <LucideLoaderCircle className="size-12 animate-spin opacity-75" />
    </div>
);

export const GenericEmbedInvalidContentMessage = ({ message }: {  message?: string }) => {
    return (
        <div className="absolute inset-0 z-20 h-full w-full bg-black">
            <div className="flex h-full flex-row items-center justify-center gap-4 p-6 text-center text-white">
                <LucideInfo className="size-12 text-destructive" />
                <p>{message || 'Invalid Embed'}</p>
            </div>
        </div>
    );
};

export const GenericEmbedConsentRequest = ({ accept, url, link }: { accept: () => void, url: string, link?: string}): ReactNode => {
    return (
        <div className="flex h-full flex-col items-center justify-center space-y-4 p-6 text-center text-white">
            <p className="text-base font-medium text-balance text-zinc-400">
                <T
                    ns="embeds"
                    k="generic.consent.text"
                />
            </p>
            <button
                onClick={accept}
                className="text-md rounded bg-zinc-600 px-4 py-2 font-bold text-white transition hover:bg-zinc-500 hover:text-white"
            >
                <T
                    ns="embeds"
                    k="generic.consent.button"
                />
            </button>

            <a
                href={link || url}
                target="_blank"
                rel="noopener noreferrer"
                className="text-md text-zinc-500 underline hover:text-zinc-300"
            >
                <T
                    ns="embeds"
                    k="generic.consent.link-text"
                />
            </a>
        </div>
    )
}
