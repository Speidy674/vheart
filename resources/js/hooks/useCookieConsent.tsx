import {
    cookieConsentManager,
} from '@/lib/cookieConsent';
import { useSyncExternalStore } from 'react';

export interface CookieConsent {
    [key: string]: boolean;
}

export function useCookieConsent(): {
    consent: CookieConsent;
    hasCookieConsent: (key: string) => boolean;
} {
    const consent = useSyncExternalStore(
        (cb) => cookieConsentManager.subscribe(cb),
        () => cookieConsentManager.getState(),
        () => ({}) as CookieConsent,
    );

    return {
        consent,
        hasCookieConsent: (key: string): boolean => consent?.[key] === true,
    };
}
