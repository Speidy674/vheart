import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

/**
 * Checks if the given element is in the viewport with a buffer
 *
 * @param el Element to check
 * @param viewBuffer Buffer in Pixels, default 0
 */
export function checkInView(el: HTMLElement, viewBuffer: number) {
    viewBuffer = viewBuffer || 0;

    const rect = el.getBoundingClientRect();

    const isVisibleVertically =
        rect.top < window.innerHeight + viewBuffer &&
        rect.bottom >= 0 - viewBuffer;

    const isVisibleHorizontally =
        rect.left < window.innerWidth + viewBuffer &&
        rect.right >= 0 - viewBuffer;

    return isVisibleVertically && isVisibleHorizontally;
}
