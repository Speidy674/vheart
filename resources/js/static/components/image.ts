import { AlpineComponent } from 'alpinejs';
import { checkInView } from '@/lib/utils';

export type ImageStatus = 'loading' | 'loaded' | 'error';

export interface ImageConfig {
    viewBuffer: number;
}

export interface ImageData {
    shown: boolean;
    imageStatus: ImageStatus;
    isCached: boolean;
    viewBuffer: number;
    checkCached(el: HTMLImageElement): void;
    show(): void;
}

export default (config: ImageConfig): AlpineComponent<ImageData> => ({
    shown: false,
    imageStatus: 'loading',
    isCached: false,
    viewBuffer: config.viewBuffer || 100,
    init() {
        const el = this.$el as HTMLImageElement;
        this.checkCached(el);

        const inView = checkInView(el, this.viewBuffer);

        if (this.isCached || inView) {
            this.shown = true;
        }
    },
    checkCached(el: HTMLImageElement) {
        if (el.complete) {
            this.imageStatus = 'loaded';
            this.isCached = true;
        }
    },
    show() {
        this.shown = true;
    },
});
