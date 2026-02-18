import { DashboardData, type BreadcrumbItem } from '@/types';

import StaticSpaceBackground from '@/components/spacebackground';
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
        </AppLayout>
    );
}
