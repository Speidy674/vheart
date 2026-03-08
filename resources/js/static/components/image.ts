import { AlpineComponent } from 'alpinejs';

export type ImageStatus = 'loading' | 'loaded' | 'error';

export interface ImageData {
    shown: boolean;
    imageStatus: ImageStatus;
    isCached: boolean;
    checkCached(el: HTMLImageElement): void;
    show(): void;
}

export default (): AlpineComponent<ImageData> => ({
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
});
