import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { Monitor, Moon, Sun } from 'lucide-react';
import * as React from 'react';

interface AppearanceToggleSliderProps extends React.HTMLAttributes<HTMLDivElement> {
    className?: string;
}

interface AppearanceItem {
    readonly value: 'light' | 'dark' | 'system';
    readonly icon: React.ComponentType<{ className?: string }>;
    readonly label: string;
}

const APPEARANCE_ITEMS: readonly AppearanceItem[] = [
    { value: 'light', icon: Sun, label: 'Light' },
    { value: 'dark', icon: Moon, label: 'Dark' },
    { value: 'system', icon: Monitor, label: 'System' },
] as const;

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const APPEARANCE_VALUES = ['light', 'dark', 'system'] as const;
type AppearanceValue = (typeof APPEARANCE_VALUES)[number];

export default function AppearanceToggleSlider({
    className,
    ...props
}: AppearanceToggleSliderProps): React.ReactElement {
    const { appearance, updateAppearance } = useAppearance();
    const [isMounted, setIsMounted] = React.useState(false);

    const activeIndex = Math.max(
        0,
        APPEARANCE_ITEMS.findIndex((item) => item.value === appearance),
    );

    const handleAppearanceChange = React.useCallback(
        (newAppearance: AppearanceValue): void => {
            updateAppearance(newAppearance);
        },
        [updateAppearance],
    );

    React.useEffect(() => {
        setIsMounted(true);
    }, []);

    if (!isMounted) {
        return (
            <div
                className={cn(
                    'relative inline-flex items-center rounded-lg',
                    'w-[92px] sm:w-[104px]',
                    'bg-neutral-100 p-1 dark:bg-neutral-800',
                    className,
                )}
                {...props}
            />
        );
    }

    return (
        <div
            className={cn(
                'relative inline-flex items-center rounded-lg',
                'w-[92px] sm:w-[104px]',
                'bg-neutral-100 p-1 dark:bg-neutral-800',
                className,
            )}
            role="group"
            aria-label="Select appearance mode"
            {...props}
        >
            <div
                className={cn(
                    'pointer-events-none absolute top-1 left-1 h-[calc(100%-0.5rem)]',
                    'w-[calc((100%-0.5rem)/3)] rounded-md',
                    'bg-white shadow-sm ring-1 ring-neutral-200',
                    'dark:bg-neutral-700 dark:shadow-none dark:ring-neutral-600',
                    'transition-transform duration-200 ease-out',
                )}
                style={{ transform: `translateX(calc(${activeIndex} * 100%))` }}
                aria-hidden="true"
            />

            {APPEARANCE_ITEMS.map(({ value, icon: Icon, label }) => {
                const isActive = appearance === value;

                return (
                    <button
                        key={value}
                        type="button"
                        onClick={() => handleAppearanceChange(value)}
                        className={cn(
                            'relative z-10 inline-flex h-6 flex-1 items-center justify-center rounded-md transition-colors',
                            'focus-visible:ring-2 focus-visible:ring-neutral-400/60 focus-visible:outline-none',
                            'dark:focus-visible:ring-neutral-500/60',
                            isActive
                                ? 'text-neutral-900 dark:text-neutral-50'
                                : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-50',
                        )}
                        aria-label={label}
                        aria-pressed={isActive}
                        title={`Switch to ${label} mode`}
                    >
                        <Icon className="h-4 w-4" aria-hidden="true" />
                        <span className="sr-only">{label}</span>
                    </button>
                );
            })}
        </div>
    );
}
