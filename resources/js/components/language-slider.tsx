import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuPortal,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';
import { Check, ChevronUp, Globe } from 'lucide-react';
import React, { useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';

const LANGUAGES = [
    { value: 'en', label: 'English' },
    { value: 'de', label: 'Deutsch' },
];

const LanguageToggleDropdown = React.memo(() => {
    const { i18n } = useTranslation();
    const [open, setOpen] = useState(false);

    const current = useMemo(() => {
        return (
            LANGUAGES.find((l) => i18n.language.startsWith(l.value)) ||
            LANGUAGES[0]
        );
    }, [i18n.language]);

    return (
        <DropdownMenu open={open} onOpenChange={setOpen} modal={false}>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    className="flex h-8 gap-2 rounded-full bg-gray-400 px-3 text-xs font-bold text-white shadow-sm hover:bg-[#d8347d] hover:text-white dark:bg-black"
                >
                    <Globe className="size-3.5" />
                    <span className="uppercase">{current.value}</span>
                    <ChevronUp
                        className={cn(
                            'size-3 transition-transform duration-300',
                            open ? 'rotate-0' : 'rotate-180',
                        )}
                    />
                </Button>
            </DropdownMenuTrigger>

            <DropdownMenuPortal>
                <DropdownMenuContent
                    side="top"
                    align="end"
                    sideOffset={15}
                    onCloseAutoFocus={(e) => e.preventDefault()}
                    onEscapeKeyDown={(e) => e.preventDefault()}
                    className="z-[110] min-w-[130px] rounded-xl border border-gray-200 bg-white/95 p-1.5 shadow-2xl backdrop-blur-xl dark:border-white/10 dark:bg-[#0a0a0a]/95"
                >
                    {LANGUAGES.map((lang) => (
                        <DropdownMenuItem
                            key={lang.value}
                            onSelect={() => i18n.changeLanguage(lang.value)}
                            className={cn(
                                'flex cursor-pointer items-center justify-between rounded-lg px-3 py-2 text-xs transition-colors focus:bg-accent focus:text-accent-foreground',
                                current.value === lang.value
                                    ? 'font-bold text-[#e9458e]'
                                    : 'text-gray-600 dark:text-white/60',
                            )}
                        >
                            {lang.label}
                            {current.value === lang.value && (
                                <Check className="size-3" />
                            )}
                        </DropdownMenuItem>
                    ))}
                </DropdownMenuContent>
            </DropdownMenuPortal>
        </DropdownMenu>
    );
});

LanguageToggleDropdown.displayName = 'LanguageToggleDropdown';

export default LanguageToggleDropdown;
