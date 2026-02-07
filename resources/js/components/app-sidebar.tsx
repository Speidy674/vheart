import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarMenu,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { type ReactNode, useEffect } from 'react';
interface AppSidebarProps {
    className?: string;
    children?: ReactNode;
}

export function AppSidebar({ className, children }: AppSidebarProps) {
    useEffect(() => {
        const updateSidebarPadding = () => {
            const footerHeight = getComputedStyle(document.documentElement)
                .getPropertyValue('--footer-height')
                .trim();

            if (footerHeight) {
                const sidebar = document.querySelector('[data-sidebar]');
                if (sidebar) {
                    (sidebar as HTMLElement).style.paddingBottom = footerHeight;
                }
            }
        };

        updateSidebarPadding();
        const interval = setInterval(updateSidebarPadding, 100);

        return () => clearInterval(interval);
    }, []);

    return (
        <Sidebar
            collapsible="icon"
            variant="inset"
            className={className}
            style={{
                zIndex: 50,
                position: 'fixed',
                bottom: 'var(--footer-height, 0px)',
                top: 0,
                left: 0,
            }}
            data-sidebar="true"
        >
            {/* Main content area */}
            {children && <SidebarContent>{children}</SidebarContent>}

            {/* Footer navigation */}
            <SidebarFooter>
                <SidebarMenu className="mt-auto"></SidebarMenu>

                <SidebarTrigger />
            </SidebarFooter>
        </Sidebar>
    );
}
