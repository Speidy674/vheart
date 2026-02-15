import { AppContent } from '@/components/app-content';
import { AppHeader } from '@/components/app-header';
import { AppShell } from '@/components/app-shell';
import type { PropsWithChildren } from 'react';
import Footer from '@/components/footer/footer';

type AppHeaderLayoutProps = PropsWithChildren<{
    isIsland?: boolean;
}>;

export default function AppHeaderLayout({
    children,
    isIsland
}: AppHeaderLayoutProps) {
    return (
        <AppShell>
            <AppHeader isIsland={isIsland} />
            <AppContent>{children}</AppContent>
            <Footer isIsland={isIsland} />
        </AppShell>
    );
}
