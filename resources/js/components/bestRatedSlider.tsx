import { ClipModal } from '@/components/clipModal';
import 'keen-slider/keen-slider.min.css';
import { useKeenSlider } from 'keen-slider/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useState } from 'react';
import { ClipPreview } from './clip-preview';
import { PublicClip } from '@/types';

export function BestRatedSlider({
    clips,
    headline = 'AM BESTEN BEWERTET DEZEMBER',
}: {
    clips: PublicClip[];
    headline?: string;
}) {
    const [openClip, setOpenClip] = useState<PublicClip | null>(null);
    const [liked, setLiked] = useState<Set<number>>(new Set());
    const [skipped, setSkipped] = useState<Set<number>>(new Set());

    const [sliderRef, instanceRef] = useKeenSlider<HTMLDivElement>({
        loop: true,
        renderMode: 'performance',
        drag: false,
        slides: {
            perView: 1,
            spacing: 12,
        },
        breakpoints: {
            '(min-width: 640px)': {
                slides: { perView: 2, spacing: 14 },
            },
            '(min-width: 1024px)': {
                slides: { perView: 3, spacing: 15 },
            },
        },
    });

    const next = () => instanceRef.current?.next();
    const prev = () => instanceRef.current?.prev();

    const toggleLike = (id: number) => {
        if (skipped.has(id)) return;

        setLiked((prev) => {
            const nextSet = new Set(prev);
            if (nextSet.has(id)) {
                nextSet.delete(id);
            } else {
                nextSet.add(id);
            }
            return nextSet;
        });
    };

    const toggleSkip = (id: number) => {
        if (liked.has(id)) return;

        setSkipped((prev) => {
            const nextSet = new Set(prev);
            if (nextSet.has(id)) {
                nextSet.delete(id);
            } else {
                nextSet.add(id);
            }
            return nextSet;
        });
    };

    const openId = openClip?.id ?? -1;
    const isLiked = liked.has(openId);
    const isSkipped = skipped.has(openId);
    const disableLike = isSkipped;
    const disableSkip = isLiked;

    if (!clips?.length) return null;

    return (
        <>
            <section>
                <h2 className="mb-4 pt-5 text-center text-2xl font-semibold">
                    {headline}
                </h2>

                <div className="relative mx-auto w-11/12">
                    {/* Prev */}
                    <button
                        type="button"
                        onClick={prev}
                        className="absolute top-1/2 left-1 z-10 -translate-y-1/2 rounded-full bg-white/50 p-2 shadow transition-transform hover:scale-110 hover:bg-accent active:scale-95"
                        aria-label="Vorherige Clips"
                    >
                        <ChevronLeft className="h-8 w-8 text-black" />
                    </button>

                    {/* Slider */}
                    <div ref={sliderRef} className="keen-slider py-15">
                        {clips.map((it) => {
                            return (
                                <div
                                    key={it.slug}
                                    className="keen-slider__slide"
                                >
                                    <ClipPreview clip={it} onClick={() => setOpenClip(it)} />
                                </div>
                            );
                        })}
                    </div>

                    {/* Next */}
                    <button
                        type="button"
                        onClick={next}
                        className="absolute top-1/2 right-1 z-10 -translate-y-1/2 rounded-full bg-white/50 p-2 shadow transition-transform hover:scale-110 hover:bg-accent active:scale-95"
                        aria-label="Nächste Clips"
                    >
                        <ChevronRight className="h-8 w-8 text-black" />
                    </button>
                </div>
            </section>

            {openClip && (
                <ClipModal
                    clip={openClip}
                    isLiked={isLiked}
                    isSkipped={isSkipped}
                    disableLike={disableLike}
                    disableSkip={disableSkip}
                    onClose={() => setOpenClip(null)}
                    onToggleLike={toggleLike}
                    onToggleSkip={toggleSkip}
                />
            )}
        </>
    );
}
