import * as AvatarPrimitive from '@radix-ui/react-avatar';
import * as React from 'react';

import useInstantInView from '@/hooks/use-instant-in-view';
import { cn } from '@/lib/utils';
import { useInView } from 'react-intersection-observer';

function Avatar({
    className,
    ...props
}: React.ComponentProps<typeof AvatarPrimitive.Root>) {
    return (
        <AvatarPrimitive.Root
            data-slot="avatar"
            className={cn(
                'relative flex size-8 shrink-0 overflow-hidden rounded-full',
                className,
            )}
            {...props}
        />
    );
}

function AvatarImage({
    className,
    src,
    ...props
}: React.ComponentProps<typeof AvatarPrimitive.Image>) {
    const spanRef = React.useRef<HTMLSpanElement | null>(null);
    const { isMountedInView, ref: instantRef } = useInstantInView(100);

    const { ref: observerRef, inView } = useInView({
        triggerOnce: true,
        rootMargin: '100px',
        skip: isMountedInView,
    });

    const shouldRender = isMountedInView || inView;

    return (
        <>
            {!shouldRender && (
                <span
                    ref={(node) => {
                        observerRef(node);
                        spanRef.current = node;
                        instantRef.current = node;
                    }}
                    className="pointer-events-none absolute inset-0 -z-10"
                    aria-hidden="true"
                />
            )}

            <AvatarPrimitive.Image
                src={shouldRender ? src : undefined}
                data-slot="avatar-image"
                className={cn('aspect-square size-full', className)}
                {...props}
            />
        </>
    );
}

function AvatarFallback({
    className,
    ...props
}: React.ComponentProps<typeof AvatarPrimitive.Fallback>) {
    return (
        <AvatarPrimitive.Fallback
            data-slot="avatar-fallback"
            className={cn(
                'flex size-full items-center justify-center rounded-full bg-muted',
                className,
            )}
            {...props}
        />
    );
}

export { Avatar, AvatarFallback, AvatarImage };
