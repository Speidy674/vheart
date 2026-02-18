import { StreamerSection } from '@/components/sidebar/streamer-section';
import {
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarSeparator,
} from '@/components/ui/sidebar';
import { isSameUrl } from '@/lib/utils';
import { clips, main, permissions } from '@/routes/dashboard';
import { DashboardData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ClapperboardIcon, HomeIcon, ShieldCheck } from 'lucide-react';
import T from '../t';

export function Dashboard_items() {
    const { url, props } = usePage<DashboardData>();
    const currentPath = url.split('?')[0];

    const navItems = [
        {
            key: 'dashboard',
            href: main(props.selectedStreamer.id),
            icon: HomeIcon,
        },
        {
            key: 'clips',
            href: clips(props.selectedStreamer.id),
            icon: ClapperboardIcon,
        },
        {
            key: 'permissions',
            href: permissions(props.selectedStreamer.id),
            icon: ShieldCheck,
        },
    ];

    return (
        <>
            <StreamerSection />

            <SidebarSeparator />

            {navItems.map((item) => {
                return (
                    <SidebarMenuItem key={item.href}>
                        <SidebarMenuButton
                            asChild
                            isActive={isSameUrl(currentPath, item.href)}
                        >
                            <Link
                                href={item.href}
                                className="flex items-center"
                            >
                                {item.icon && (
                                    <item.icon className="size-4 shrink-0" />
                                )}
                                <span>
                                    <T
                                        ns="dashboard/navigation"
                                        k={item.key}
                                    ></T>
                                </span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                );
            })}
        </>
    );
}
