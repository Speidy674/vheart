import { useLayoutEffect, useRef, useState } from 'react';

/**
 * helper hook to check if we are currently in view or not
 * also works on mount
 */
export default function useInstantInView(margin: number = 0) {
    const [isMountedInView, setIsMountedInView] = useState(false);
    const ref = useRef<HTMLElement | null>(null);

    useLayoutEffect(() => {
        if (!ref.current) return;

        async function isVisible() {
            setIsMountedInView(true);
        }

        const rect = ref.current.getBoundingClientRect();

        if (
            rect.top < window.innerHeight + margin &&
            rect.bottom > -margin &&
            rect.left < window.innerWidth + margin &&
            rect.right > -margin
        ) {
            void isVisible();
        }
    }, [margin]);

    return { isMountedInView, ref };
}
