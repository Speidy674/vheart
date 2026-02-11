import { AppContent } from '@/components/app-content';
import { AppHeader } from '@/components/app-header';
import { AppShell } from '@/components/app-shell';
import type { PropsWithChildren } from 'react';
import Footer from '@/components/footer/footer';

export default function AppHeaderLayout({
    children,
}: PropsWithChildren) {
    return (
        <AppShell>
            <AppHeader />
            <AppContent>{children}</AppContent>
            <Footer />
        </AppShell>
    );
}
