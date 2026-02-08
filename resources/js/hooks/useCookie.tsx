import { useCookieConsent } from '@/hooks/useCookieConsent';
import { useCallback, useEffect, useState } from 'react';

/**
 * Get or Set a cookie.
 *
 * If we don't have consent it will ignore it though.
 */
export function useCookie(cookieName: string) {
    const { hasCookieConsent } = useCookieConsent();
    const isAllowed = hasCookieConsent(cookieName);
    const documentAvailable = typeof document !== 'undefined'; // ssr
    const cookieStoreAvailable = documentAvailable && 'cookieStore' in window;

    const getCookieSync = useCallback(() => {
        if (!documentAvailable || !isAllowed) return null;

        const match = document.cookie.match(
            new RegExp(`(^|;\\s*)(${cookieName})=([^;]*)`),
        );

        return match ? decodeURIComponent(match[2]) : null;
    }, [cookieName, isAllowed, documentAvailable]);

    const [cookieValue, setCookieValue] = useState<string | null>(() =>
        getCookieSync(),
    );

    /**
     * Listen for changes (CookieStore API)
     */
    useEffect(() => {
        if (!cookieStoreAvailable || !isAllowed) return;

        const handleChange = (event: CookieChangeEvent) => {
            const changed = event.changed.find(
                (c: CookieListItem) => c.name === cookieName,
            );
            const deleted = event.deleted.find(
                (c: CookieListItem) => c.name === cookieName,
            );

            if (changed && changed.value) {
                setCookieValue(decodeURIComponent(changed.value));
            } else if (deleted) {
                setCookieValue(null);
            }
        };

        window.cookieStore.addEventListener('change', handleChange);

        return () => {
            window.cookieStore.removeEventListener('change', handleChange);
        };
    }, [cookieName, cookieStoreAvailable, isAllowed]);

    /**
     * Set the Cookie
     */
    const set = useCallback(
        (
            value: string,
            options: {
                days?: number;
                path?: string;
                sameSite?: CookieSameSite;
                secure?: boolean;
            } = {},
        ): boolean => {
            if (!documentAvailable) return false;

            if (!isAllowed) {
                console.debug(
                    `Cookie ${cookieName} shall not pass (missing consent)`,
                );
                return false;
            }

            const {
                days = 365,
                path = '/',
                sameSite = 'lax',
                secure = true,
            } = options;

            const date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);

            if (cookieStoreAvailable) {
                window.cookieStore
                    .set({
                        name: cookieName,
                        value: encodeURIComponent(value),
                        expires: date.getTime(),
                        path,
                        sameSite: sameSite,
                    })
                    .then(() => {
                        setCookieValue(value);
                    })
                    .catch((e: unknown) => {
                        console.error('CookieStore set failed', e);
                    });

                return true;
            }

            let cookieString = `${cookieName}=${encodeURIComponent(value)}; expires=${date.toUTCString()}; path=${path}; SameSite=${sameSite}`;

            if (secure) {
                cookieString += '; Secure';
            }

            document.cookie = cookieString;
            setCookieValue(value);
            return true;
        },
        [documentAvailable, isAllowed, cookieStoreAvailable, cookieName],
    );

    return [cookieValue, set] as const;
}
