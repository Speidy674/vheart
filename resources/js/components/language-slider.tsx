import { cn } from '@/lib/utils';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/react';
import * as React from 'react';
import { useTranslation } from 'react-i18next';

interface LanguageToggleDropdownProps extends React.HTMLAttributes<HTMLDivElement> {
    className?: string;
}

interface LanguageItem {
    readonly value: string;
    readonly label: string;
    readonly flag?: string;
}

const LANGUAGE_ITEMS: readonly LanguageItem[] = [
    { value: 'en', label: 'English' },
    { value: 'de', label: 'Deutsch' },
] as const;

export default function LanguageToggleDropdown({
    className,
    ...props
}: LanguageToggleDropdownProps): React.ReactElement {
    const { i18n } = useTranslation();
    const [isLoading, setIsLoading] = React.useState(false);

    const currentLanguage =
        LANGUAGE_ITEMS.find((item) => i18n.language.startsWith(item.value)) ||
        LANGUAGE_ITEMS[0];

    const handleLanguageChange = async (language: string) => {
        if (language === currentLanguage.value || isLoading) return;

        setIsLoading(true);
        try {
            const response = await fetch(`/locales/${language}`);
            if (!response.ok)
                throw new Error(`Backend error: ${response.status}`);
            await i18n.changeLanguage(language);
        } catch (error) {
            console.error('Language change failed:', error);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className={cn('relative', className)} {...props}>
            <Menu>
                <MenuButton
                    disabled={isLoading}
                    className={cn(
                        'inline-flex items-center gap-1.5 rounded-md px-2 py-1',
                        'text-gray-600 hover:text-gray-900',
                        'dark:text-white/70 dark:hover:text-white',
                        'transition-colors duration-150',
                        'text-xs sm:text-sm',
                        'focus-visible:ring-2 focus-visible:ring-black/20 focus-visible:outline-none',
                        'dark:focus-visible:ring-white/20',
                        isLoading && 'opacity-50',
                    )}
                    aria-label="Change language"
                >
                    <span className="text-sm">🌐</span>
                    <span>{currentLanguage.value.toUpperCase()}</span>
                    <span className="text-[10px]">▼</span>
                </MenuButton>

                <MenuItems
                    transition
                    className={cn(
                        'absolute right-0 bottom-full z-50 mb-2',
                        'min-w-[110px] rounded border shadow-lg',
                        'bg-white/95 backdrop-blur-sm dark:bg-black/95',
                        'border-gray-200 dark:border-gray-700',
                        'origin-top-right transition duration-100 ease-out',
                        'data-[closed]:scale-95 data-[closed]:opacity-0',
                    )}
                >
                    <div className="py-1">
                        {LANGUAGE_ITEMS.map((item) => (
                            <MenuItem key={item.value}>
                                {({ focus, close }) => (
                                    <button
                                        onClick={async () => {
                                            await handleLanguageChange(
                                                item.value,
                                            );
                                            close();
                                        }}
                                        className={cn(
                                            'flex w-full items-center justify-between px-3 py-2 text-sm',
                                            'transition-colors duration-150',
                                            focus &&
                                                'bg-gray-100 dark:bg-gray-800',
                                            currentLanguage.value ===
                                                item.value &&
                                                'font-medium text-gray-900 dark:text-white',
                                        )}
                                    >
                                        <span
                                            className={cn(
                                                'text-gray-700 dark:text-gray-300',
                                                currentLanguage.value ===
                                                    item.value &&
                                                    'text-gray-900 dark:text-white',
                                            )}
                                        >
                                            {item.label}
                                        </span>
                                        {currentLanguage.value ===
                                            item.value && (
                                            <span className="text-xs">✓</span>
                                        )}
                                    </button>
                                )}
                            </MenuItem>
                        ))}
                    </div>
                </MenuItems>
            </Menu>
        </div>
    );
}
