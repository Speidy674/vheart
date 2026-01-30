import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { cn, isSameUrl, resolveUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editPermissions } from '@/routes/permissions';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronDown, Settings } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const sidebarNavItems: NavItem[] = [
    { title: 'nav.profile', href: edit(), icon: null },
    { title: 'nav.appearance', href: editAppearance(), icon: null },
    { title: 'nav.permissions', href: editPermissions(), icon: null },
];

export function SettingsSection() {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';
    const { t } = useTranslation('settings');

    const { url } = usePage();
    const currentPath = url.split('?')[0];

    return (
        <Collapsible
            defaultOpen
            open={isCollapsed ? false : undefined}
            className="group/collapsible"
        >
            <SidebarMenuItem>
                <CollapsibleTrigger asChild disabled={isCollapsed}>
                    <SidebarMenuButton>
                        <Settings className="size-4" />
                        <span className="font-medium">{t('title')}</span>
                        <ChevronDown className="ml-auto size-4 transition-transform group-data-[state=open]/collapsible:rotate-180" />
                    </SidebarMenuButton>
                </CollapsibleTrigger>

                {!isCollapsed && (
                    <CollapsibleContent>
                        <div className="mt-1 space-y-1 pl-7">
                            {sidebarNavItems.map((item, index) => {
                                const active = isSameUrl(
                                    currentPath,
                                    item.href,
                                );

                                return (
                                    <SidebarMenuItem
                                        key={`${resolveUrl(item.href)}-${index}`}
                                    >
                                        <SidebarMenuButton
                                            asChild
                                            size="sm"
                                            className={cn(
                                                'justify-start',
                                                active &&
                                                    'bg-muted text-foreground',
                                            )}
                                        >
                                            <Link href={item.href}>
                                                {item.icon && (
                                                    <item.icon className="mr-2 size-4" />
                                                )}
                                                <span>{t(item.title)}</span>
                                            </Link>
                                        </SidebarMenuButton>
                                    </SidebarMenuItem>
                                );
                            })}
                        </div>
                    </CollapsibleContent>
                )}
            </SidebarMenuItem>
        </Collapsible>
    );
}
