import { cn } from '@/lib/utils';
import * as React from 'react';
import { useTranslation } from 'react-i18next';

interface LanguageToggleSliderProps extends React.HTMLAttributes<HTMLDivElement> {
    className?: string;
}

interface LanguageItem {
    readonly value: string;
    readonly label: string;
}

const LANGUAGE_ITEMS: readonly LanguageItem[] = [
    { value: 'en', label: 'EN' },
    { value: 'de', label: 'DE' },
] as const;

const LOCAL_STORAGE_LANGUAGE_KEY = 'i18nextLng';

export default function LanguageToggleSlider({
    className,
    ...props
}: LanguageToggleSliderProps): React.ReactElement {
    const { i18n } = useTranslation();
    const [isMounted, setIsMounted] = React.useState(false);

    const currentLanguage = i18n.language.startsWith('de') ? 'de' : 'en';
    const activeIndex = LANGUAGE_ITEMS.findIndex(
        (item) => item.value === currentLanguage,
    );

    const handleLanguageChange = React.useCallback(
        (language: string): void => {
            i18n.changeLanguage(language);
            try {
                localStorage.setItem(LOCAL_STORAGE_LANGUAGE_KEY, language);
            } catch (error) {
                console.warn(
                    'Failed to save language preference to localStorage:',
                    error,
                );
            }
        },
        [i18n],
    );

    React.useEffect(() => {
        setIsMounted(true);
    }, []);

    if (!isMounted) {
        return (
            <div
                className={cn(
                    'relative inline-flex w-full items-center rounded-lg',
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
                'relative inline-flex w-full items-center rounded-lg',
                'bg-neutral-100 p-1 dark:bg-neutral-800',
                className,
            )}
            role="group"
            aria-label="Select language"
            {...props}
        >
            <div
                className={cn(
                    'pointer-events-none absolute top-1 left-1 h-[calc(100%-0.5rem)]',
                    'w-[calc((100%-0.5rem)/2)] rounded-md',
                    'bg-white shadow-sm ring-1 ring-neutral-200',
                    'dark:bg-neutral-700 dark:shadow-none dark:ring-neutral-600',
                    'transition-transform duration-200 ease-out',
                )}
                style={{
                    transform: `translateX(calc(${activeIndex} * 100%))`,
                }}
                aria-hidden="true"
            />

            {LANGUAGE_ITEMS.map(({ value, label }) => {
                const isActive = currentLanguage === value;

                return (
                    <button
                        key={value}
                        type="button"
                        onClick={() => handleLanguageChange(value)}
                        className={cn(
                            'relative z-10 inline-flex h-6 flex-1 items-center justify-center',
                            'rounded-md text-xs font-medium transition-colors',
                            'focus-visible:ring-2 focus-visible:ring-neutral-400/60',
                            'focus-visible:outline-none dark:focus-visible:ring-neutral-500/60',
                            isActive
                                ? 'text-neutral-900 dark:text-neutral-50'
                                : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-50',
                        )}
                        aria-pressed={isActive}
                        aria-label={`Switch to ${label}`}
                    >
                        {label}
                    </button>
                );
            })}
        </div>
    );
}
