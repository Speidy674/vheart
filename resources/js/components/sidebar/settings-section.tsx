import { SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { cn, isSameUrl, resolveUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editPermissions } from '@/routes/permissions';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Settings } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const sidebarNavItems: NavItem[] = [
    { title: 'nav.profile', href: edit(), icon: null },
    { title: 'nav.appearance', href: editAppearance(), icon: null },
    { title: 'nav.permissions', href: editPermissions(), icon: null },
];

export function SettingsSection() {
    const { t } = useTranslation('settings');

    const { url } = usePage();
    const currentPath = url.split('?')[0];

    return (
        <>
            {/* Section label */}
            <SidebarMenuItem>
                <div className="flex items-center gap-2 px-2 py-1.5 text-xs font-medium text-black uppercase dark:text-white">
                    <Settings className="size-4" />
                    <span>{t('title')}</span>
                </div>
            </SidebarMenuItem>

            {/* Sub items */}
            <div className="mt-1 space-y-1 pl-7">
                {sidebarNavItems.map((item, index) => {
                    const active = isSameUrl(currentPath, item.href);

                    return (
                        <SidebarMenuItem
                            key={`${resolveUrl(item.href)}-${index}`}
                        >
                            <SidebarMenuButton
                                asChild
                                size="sm"
                                className={cn(
                                    'justify-start',
                                    active && 'bg-muted text-foreground',
                                )}
                            >
                                <Link href={item.href}>
                                    {item.icon && (
                                        <item.icon className="mr-2 size-4" />
                                    )}
                                    <span>{t(item.title)}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    );
                })}
            </div>
        </>
    );
}
