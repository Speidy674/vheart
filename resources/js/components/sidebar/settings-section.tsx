import {
    SidebarGroupLabel,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { isSameUrl, resolveUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editPermissions } from '@/routes/permissions';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Eye, Settings, ShieldCheck, User } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const sidebarNavItems: NavItem[] = [
    { title: 'nav.profile', href: edit(), icon: User },
    { title: 'nav.appearance', href: editAppearance(), icon: Eye },
    { title: 'nav.permissions', href: editPermissions(), icon: ShieldCheck },
];

export function SettingsSection() {
    const { t } = useTranslation('settings');
    const { url } = usePage();
    const currentPath = url.split('?')[0];

    return (
        <SidebarMenuItem>
            <SidebarGroupLabel className="flex items-center gap-2 text-sm font-medium">
                <Settings className="size-4 shrink-0" />
                <span className="group-data-[collapsible=icon]:hidden">
                    {t('title')}
                </span>
            </SidebarGroupLabel>

            <SidebarMenuSub>
                {sidebarNavItems.map((item, index) => {
                    const active = isSameUrl(currentPath, item.href);
                    const Icon = item.icon;

                    return (
                        <SidebarMenuSubItem
                            key={`${resolveUrl(item.href)}-${index}`}
                        >
                            <SidebarMenuSubButton asChild isActive={active}>
                                <Link
                                    href={item.href}
                                    className="flex items-center gap-2"
                                >
                                    {Icon && (
                                        <Icon className="size-4 shrink-0" />
                                    )}
                                    <span>{t(item.title)}</span>
                                </Link>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                    );
                })}
            </SidebarMenuSub>
        </SidebarMenuItem>
    );
}
