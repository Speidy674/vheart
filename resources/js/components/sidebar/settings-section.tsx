import {
    SidebarHeader,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarSeparator,
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
        <>
            <SidebarHeader className="flex-row items-center group-data-[collapsible=icon]:hidden">
                <Settings className="size-4 shrink-0" />
                <span>{t('title')}</span>
            </SidebarHeader>

            <SidebarSeparator />

            {sidebarNavItems.map((item, index) => {
                const active = isSameUrl(currentPath, item.href);
                const Icon = item.icon;

                return (
                    <SidebarMenuItem key={`${resolveUrl(item.href)}-${index}`}>
                        <SidebarMenuButton asChild isActive={active}>
                            <Link
                                href={item.href}
                                className="flex items-center gap-2"
                            >
                                {Icon && <Icon className="size-4 shrink-0" />}
                                <span>{t(item.title)}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                );
            })}
        </>
    );
}
