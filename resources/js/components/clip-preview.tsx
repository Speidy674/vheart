import { PublicClip } from '@/types';
import { Clock, Heart, Image as ImageIcon, ImageOff } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';

type ClipPreviewProps = {
    clip: PublicClip;
    onClick?: () => void;
};

const formatDuration = (seconds: number) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${String(s).padStart(2, '0')}`;
};

type ImageStatus = 'loading' | 'loaded' | 'error';

export function ClipPreview({ clip, onClick }: ClipPreviewProps) {
    const [imageStatus, setImageStatus] = useState<ImageStatus>('loading');
    const imgRef = useRef<HTMLImageElement>(null);

    useEffect(() => {
        const img = imgRef.current;
        async function updateState(state: ImageStatus) {
            setImageStatus(state);
        }

        if (img && img.complete) {
            // If image is already cached/loaded, update state immediately
            if (img.naturalWidth > 0) {
                void updateState('loaded');
            } else {
                void updateState('error');
            }
        }
    }, []);

    return (
        <button
            type="button"
            onClick={onClick}
            aria-label={`Clip öffnen: ${clip.title}`}
            className="group relative aspect-video w-full overflow-hidden rounded bg-gray-200 drop-shadow-md dark:bg-gray-800 dark:drop-shadow-white/20"
        >
            {/* Loading */}
            {imageStatus === 'loading' && (
                <div className="absolute inset-0 flex items-center justify-center bg-gray-200 text-gray-400 dark:bg-gray-800 dark:text-gray-600">
                    <ImageIcon className="h-8 w-8 animate-pulse" />
                </div>
            )}

            {/* Error */}
            {imageStatus === 'error' && (
                <div className="absolute inset-0 flex items-center justify-center bg-gray-300 text-gray-500 dark:bg-gray-800 dark:text-gray-500">
                    <ImageOff className="h-8 w-8" />
                </div>
            )}

            {/* Image */}
            <img
                ref={imgRef}
                src={clip.thumbnail_url}
                alt={clip.title}
                className={`w-full object-cover ${
                    imageStatus === 'loaded' ? 'opacity-100' : 'opacity-0'
                }`}
                loading="lazy"
                decoding="async"
                onLoad={() => setImageStatus('loaded')}
                onError={() => setImageStatus('error')}
            />

            {/* Länge */}
            <div className="absolute top-2 left-2 flex items-center gap-1 rounded-lg bg-black/60 px-2 py-1 text-xs text-white">
                <Clock className="size-4" />
                {formatDuration(clip.clip_duration)}
            </div>

            {/* Likes */}
            <div className="absolute top-2 right-2 flex items-center gap-1 rounded-lg bg-black/60 px-2 py-1 text-xs text-white">
                <Heart className="size-4 text-red-500" />
                {clip.votes ?? 0}
            </div>

            {/* Titel unten */}
            <div className="absolute right-2 bottom-0.5 left-2 rounded-xl bg-black/75 px-2 py-1 text-white">
                <div className="line-clamp-1 text-sm font-medium">
                    {clip.title}
                </div>

                {clip.broadcaster && (
                    <div className="truncate text-xs text-white/80">
                        {clip.broadcaster.name}
                    </div>
                )}
            </div>
        </button>
    );
}
