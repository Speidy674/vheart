import { SettingsSection } from '@/components/sidebar/settings-section';
import { StreamerSection } from '@/components/sidebar/streamer-section';
import { SidebarMenu } from '@/components/ui/sidebar';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
    withSidebar?: boolean;
}

export default function AppLayout({
    children,
    breadcrumbs,
    withSidebar = false,
    ...props
}: AppLayoutProps) {
    const sidebarContent = withSidebar ? (
        <SidebarMenu>
            <StreamerSection />
            <SettingsSection />
        </SidebarMenu>
    ) : null;

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
