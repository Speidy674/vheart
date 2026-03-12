import { Dashboard_items } from '@/components/sidebar/dashboard_items';
import { SidebarMenu } from '@/components/ui/sidebar';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

type SidebarVariant = 'none' | 'creator_dashboard' | 'personal_settings';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
    sidebarVariant?: SidebarVariant;
}

export default function AppLayout({
    children,
    breadcrumbs,
    sidebarVariant = 'none',
    ...props
}: AppLayoutProps) {
    const sidebarContent =
        sidebarVariant === 'none' ? null : (
            <SidebarMenu>
                {sidebarVariant === 'creator_dashboard' && (
                    <>
                        <Dashboard_items />
                    </>
                )}
            </SidebarMenu>
        );

    return (
        <AppLayoutTemplate
            breadcrumbs={breadcrumbs}
            sidebarContent={sidebarContent}
            {...props}
        >
            {children}
        </AppLayoutTemplate>
    );
}
