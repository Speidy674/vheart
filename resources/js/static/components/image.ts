import { AlpineComponent } from 'alpinejs';

export type ImageStatus = 'loading' | 'loaded' | 'error';

export interface ImageData {
    src: string;
    alt: string;
    shown: boolean;
    imageStatus: ImageStatus;
    isCached: boolean;
    imageBindings: Record<string, unknown>;
    checkCached(el: HTMLImageElement): void;
    show(): void;
}

export default (src: string, alt: string): AlpineComponent<ImageData> => ({
    src,
    alt,
    shown: false,
    imageStatus: 'loading',
    isCached: false,

    checkCached(el: HTMLImageElement) {
        if (el.complete && el.naturalWidth > 0) {
            this.imageStatus = 'loaded';
            this.isCached = true;
        }
    },

    show() {
        this.shown = true;
    },

    imageBindings: {
        [':src']() {
            return this.src;
        },
        [':alt']() {
            return this.alt;
        },
        ['@load']() {
            this.imageStatus = 'loaded';
        },
        ['@error']() {
            this.imageStatus = 'error';
        },
        [':data-status']() {
            return this.imageStatus;
        },
        [':data-cached']() {
            return this.isCached ? 'true' : 'false';
        },
        ['loading']: 'lazy',
        ['decoding']: 'async',
    },
});
