import { Card } from '@/components/ui/card';
import { useEffect, useRef, useState } from 'react';
import { Youtube } from 'lucide-react';
import YoutubeEmbed from '@/components/embeds/youtube-embed';

type Props = {
    triggerWord: string;
    imageUrl?: string;
    videoUrl?: string;
    creditText?: string;
    creditLabel?: string;
    creditHref?: string;
};

export default function EasterEggModal({
    triggerWord,
    imageUrl,
    videoUrl,
    creditText,
    creditLabel,
    creditHref,
}: Props) {
    const [isOpen, setIsOpen] = useState(false);
    // eslint-disable-next-line react-hooks/purity
    const [imageKey, setImageKey] = useState(Date.now());

    const lastFocusEl = useRef<HTMLElement | null>(null);
    const bufferRef = useRef('');
    const closeButtonRef = useRef<HTMLButtonElement>(null);

    const isTextInput = (el: HTMLElement | null) => {
        if (!el) return false;
        const tag = (el.tagName || '').toLowerCase();
        return tag === 'input' || tag === 'textarea' || el.isContentEditable;
    };

    const openModal = () => {
        lastFocusEl.current = document.activeElement as HTMLElement;
        setImageKey(Date.now());
        setIsOpen(true);
    };

    const closeModal = () => {
        setIsOpen(false);
        lastFocusEl.current?.focus?.({ preventScroll: true });
    };

    useEffect(() => {
        const normalized = triggerWord.toLowerCase();
        const len = normalized.length;

        const handleKeyDown = (e: KeyboardEvent) => {
            if (e.key === 'Escape' && isOpen) return closeModal();
            if (isTextInput(document.activeElement as HTMLElement)) return;

            const key = e.key.toLowerCase();
            if (!/^[a-z]$/.test(key)) return;

            bufferRef.current = (bufferRef.current + key).slice(-len);

            if (bufferRef.current === normalized) {
                openModal();
                bufferRef.current = '';
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [isOpen, triggerWord]);

    useEffect(() => {
        if (!isOpen) return;

        const prev = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        closeButtonRef.current?.focus();

        return () => {
            document.body.style.overflow = prev;
        };
    }, [isOpen]);

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-[9999]">
            <div
                className="fixed inset-0 bg-black/70 backdrop-blur-sm"
                onClick={closeModal}
            />

            <div className="grid h-full w-full place-items-center p-4">
                <Card className="relative z-10 w-full max-w-[520px] overflow-hidden rounded-2xl bg-white/90 p-6 shadow-2xl backdrop-blur-xl dark:bg-black/70">
                    <button
                        ref={closeButtonRef}
                        onClick={closeModal}
                        className="absolute top-3 right-3 z-[999] flex h-10 w-10 items-center justify-center rounded-full bg-black/60 text-white hover:bg-black/80"
                    >
                        ✕
                    </button>

                    <div className="flex flex-col items-center gap-4">
                        <div className="relative z-0 w-full overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800">
                            {videoUrl ? (
                                <YoutubeEmbed url={videoUrl} />
                            ) : (
                                imageUrl && (
                                    <img
                                        key={imageKey}
                                        src={`${imageUrl}${imageUrl.includes('?') ? '&' : '?'}t=${imageKey}`}
                                        alt="Easter Egg"
                                        className="h-auto w-full object-contain"
                                        loading="lazy"
                                    />
                                )
                            )}
                        </div>

                        {(creditText || creditLabel) && (
                            <small className="text-center text-sm text-gray-600 dark:text-gray-400">
                                {creditText}{' '}
                                {creditHref ? (
                                    <a
                                        href={creditHref}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="font-medium text-purple-600 hover:underline"
                                    >
                                        {creditLabel}
                                    </a>
                                ) : (
                                    <span className="font-medium">
                                        {creditLabel}
                                    </span>
                                )}
                            </small>
                        )}
                    </div>
                </Card>
            </div>
        </div>
    );
}
