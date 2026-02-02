import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Appearance, useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { Eye, Monitor, Moon, Sun } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const items: {
    value: Appearance;
    label: string;
    icon: React.ElementType;
}[] = [
    { value: 'light', label: 'Light', icon: Sun },
    { value: 'dark', label: 'Dark', icon: Moon },
    { value: 'system', label: 'System', icon: Monitor },
];

export function Appearance_dropdown() {
    const { appearance, updateAppearance } = useAppearance();
    const { t } = useTranslation('settings');

    return (
        <DropdownMenuItem
            onSelect={(e) => e.preventDefault()}
            className="flex cursor-default items-center justify-between gap-3"
        >
            <span className="flex items-center gap-2 text-sm">
                <Eye className="mr-2 h-4 w-4" />
                {t('nav.appearance')}
            </span>

            <div className="flex items-center gap-1">
                {items.map(({ value, icon: Icon, label }) => {
                    const isActive = appearance === value;

                    return (
                        <button
                            key={value}
                            onClick={() => updateAppearance(value)}
                            aria-label={label}
                            className={cn(
                                'flex size-7 items-center justify-center rounded-md transition-colors',
                                'hover:bg-neutral-200/60 dark:hover:bg-neutral-700/60',
                                isActive &&
                                    'bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white',
                            )}
                        >
                            <Icon className="h-4 w-4" />
                        </button>
                    );
                })}
            </div>
        </DropdownMenuItem>
    );
}
