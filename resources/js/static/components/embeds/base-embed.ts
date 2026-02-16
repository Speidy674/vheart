import { AlpineComponent } from 'alpinejs';

export interface GenericEmbedConfig {
    url?: string;
    link?: string;
    cookieName?: string;
    title?: string;
}

export interface GenericEmbedData {
    url: string | null;
    link: string | null;
    title: string;
    cookieName: string | null;
    isLoading: boolean;
    hasConsentGiven: boolean;
    isValidUrl: boolean;
    hasConsent(): boolean;
    hasCookie(name: string): boolean;
    accept(): void;
    handleIframeLoad(): void;
    init(): void;
}

export default (
    config: GenericEmbedConfig,
): AlpineComponent<GenericEmbedData> => ({
    url: config.url || null,
    link: config.link || null,
    cookieName: config.cookieName || null,
    title: config.title || 'Embed',
    isLoading: true,
    hasConsentGiven: false,
    isValidUrl: true,

    init() {
        this.$watch('url', () => {
            this.isLoading = true;

            if (!this.url || this.url.length === 0) {
                this.isValidUrl = false;
                return;
            }

            try {
                new URL(this.url);
                this.isValidUrl = true;

                // eslint-disable-next-line @typescript-eslint/no-unused-vars
            } catch (error) {
                this.isValidUrl = false;
            }
        });

        console.debug('Embed data initialized for ' + this.url);
    },

    hasConsent() {
        return (
            !this.cookieName ||
            this.hasCookie(this.cookieName) ||
            this.hasConsentGiven
        );
    },

    hasCookie(name: string) {
        if (typeof document === 'undefined') return false;
        return document.cookie
            .split('; ')
            .some((row) => row.startsWith(name + '='));
    },

    accept() {
        this.hasConsentGiven = true;
        if (this.cookieName) {
            const date = new Date();
            date.setTime(date.getTime() + 30 * 24 * 60 * 60 * 1000); // 30 days
            document.cookie = `${this.cookieName}=1; expires=${date.toUTCString()}; path=/`;
        }
    },

    handleIframeLoad() {
        this.isLoading = false;
    },
});
