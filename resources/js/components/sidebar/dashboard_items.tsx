import { StreamerSection } from '@/components/sidebar/streamer-section';
import {
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { isSameUrl } from '@/lib/utils';
import { manage_clips, manage_permissions } from '@/routes';
import { Link, usePage } from '@inertiajs/react';
import { Scissors, ShieldCheck } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export function Dashboard_items() {
    const { t } = useTranslation('dashboard');
    const { url } = usePage();
    const currentPath = url.split('?')[0];

    const items = [
        { title: 'nav.manage_clips', href: manage_clips().url, icon: Scissors },
        {
            title: 'nav.permissions',
            href: manage_permissions().url,
            icon: ShieldCheck,
        },
    ];

    return (
        <SidebarMenuItem>
            <StreamerSection />

            <SidebarMenuSub>
                {items.map((item) => {
                    const active = isSameUrl(currentPath, item.href);
                    const Icon = item.icon;

                    return (
                        <SidebarMenuSubItem key={item.href}>
                            <SidebarMenuSubButton asChild isActive={active}>
                                <Link
                                    href={item.href}
                                    className="flex items-center gap-2"
                                >
                                    <Icon className="size-4 shrink-0" />
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
