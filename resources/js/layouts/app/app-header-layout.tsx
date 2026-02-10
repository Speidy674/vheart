import { AppContent } from '@/components/app-content';
import { AppHeader } from '@/components/app-header';
import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';
import type { PropsWithChildren } from 'react';

type AppHeaderLayoutProps = PropsWithChildren<{
    breadcrumbs?: BreadcrumbItem[];
    isIsland?: boolean;
}>;

export default function AppHeaderLayout({
    children,
    breadcrumbs,
    isIsland,
}: AppHeaderLayoutProps) {
    return (
        <AppShell>
            <AppHeader breadcrumbs={breadcrumbs} isIsland={isIsland} />
            <AppContent>{children}</AppContent>
        </AppShell>
    );
}
