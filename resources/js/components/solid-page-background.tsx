import { useLayoutEffect, useRef } from 'react';

export default function SolidPageBackground() {
    const ref = useRef<HTMLDivElement>(null);

    useLayoutEffect(() => {
        const el = ref.current;
        if (!el) return;

        const apply = () => {
            const isDark = document.documentElement.classList.contains('dark');
            el.style.background = isDark ? '#0a0a1a' : '#EEF2F8';
        };

        apply();

        const observer = new MutationObserver(apply);
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class'],
        });

        return () => observer.disconnect();
    }, []);

    return (
        <div
            ref={ref}
            className="pointer-events-none fixed inset-0"
            style={{ zIndex: 0 }}
        />
    );
}
