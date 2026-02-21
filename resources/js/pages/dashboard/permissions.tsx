import { DashboardData, type BreadcrumbItem } from '@/types';

import StaticSpaceBackground from '@/components/spacebackground';
import T from '@/components/t';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { main, permissions } from '@/routes/dashboard';
import { Head, usePage } from '@inertiajs/react';

export default function ManagePermisssions() {
    const { props } = usePage<DashboardData>();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: main(props.selectedStreamer.id).url,
        },
        {
            title: 'Permissions',
            href: permissions(props.selectedStreamer.id).url,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs} sidebarVariant="creator_dashboard">
            <Head
                title={props.selectedStreamer.name + ' Dashboard Permissions'}
            />
            <StaticSpaceBackground />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid min-h-full grid-flow-row gap-4">
                    <div className="relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-2 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent">
                        <T ns="dashboard/permissions" k="clips"></T>
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative min-h-full overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-2 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent">
                        <T ns="dashboard/permissions" k="user"></T>
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative min-h-full overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-2 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent">
                        <T ns="dashboard/permissions" k="games"></T>
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-2 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent">
                        <T ns="dashboard/permissions" k="mods"></T>
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
