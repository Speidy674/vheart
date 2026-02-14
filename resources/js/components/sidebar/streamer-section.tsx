import { Collapsible } from '@/components/ui/collapsible';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { cn, isSameUrl } from '@/lib/utils';
import { clips, main } from '@/routes/dashboard';
import { Auth, PublicUser } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Separator } from '@radix-ui/react-separator';
import { ChevronDown, ClapperboardIcon, HomeIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import T from '../t';
import { Avatar, AvatarFallback, AvatarImage } from '../ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '../ui/dropdown-menu';

type StreamerSelectProps = {
    selectedStreamer: PublicUser;
    streamers: PublicUser[];
    auth: Auth;
};

/**
 * Streamer selection section for the sidebar.
 * Shows a collapsible list of streamers the user manages.
 */
export function StreamerSection() {
    const { state } = useSidebar();
    const { t } = useTranslation('navigation');
    const isCollapsed = state === 'collapsed';
    const { props } = usePage<StreamerSelectProps>();
    console.log('StreamerSection', props);
    const currentPath = window.location.pathname;

    const navItems = [
        { key: 'main', href: main(props.selectedStreamer.id), icon: HomeIcon },
        {
            key: 'clip',
            href: clips(props.selectedStreamer.id),
            icon: ClapperboardIcon,
        },
    ];

    return (
        <SidebarMenu>
            <Collapsible
                open={isCollapsed ? false : undefined}
                defaultOpen
                className="group/collapsible"
            >
                <SidebarMenuItem>
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild disabled={isCollapsed}>
                            <SidebarMenuButton className="hover:cursor-pointer hover:bg-transparent focus:bg-transparent active:bg-transparent data-[state=open]:bg-transparent data-[state=open]:hover:bg-transparent">
                                <Avatar className="size-7 overflow-hidden rounded-full">
                                    <AvatarImage
                                        src={props.selectedStreamer.avatar}
                                        alt={props.selectedStreamer.name}
                                    />
                                    <AvatarFallback className="rounded-full bg-neutral-200 text-xs text-black dark:bg-neutral-700 dark:text-white">
                                        {props.selectedStreamer.name.substring(
                                            0,
                                            1,
                                        )}
                                    </AvatarFallback>
                                </Avatar>
                                <span className="font-medium">
                                    {props.selectedStreamer.name}
                                </span>
                                <ChevronDown className="ml-auto size-4 transition-transform group-data-[state=open]/collapsible:rotate-180" />
                            </SidebarMenuButton>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent className="w-56" align="start">
                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild>
                                    <Link
                                        className="block w-full"
                                        href={main(props.auth.user.id)}
                                        as="button"
                                        prefetch
                                    >
                                        <Avatar className="size-7 overflow-hidden rounded-full">
                                            <AvatarImage
                                                src={props.auth.user.avatar}
                                                alt={props.auth.user.name}
                                            />
                                            <AvatarFallback className="rounded-full bg-neutral-200 text-xs text-black dark:bg-neutral-700 dark:text-white">
                                                {props.auth.user.name.substring(
                                                    0,
                                                    1,
                                                )}
                                            </AvatarFallback>
                                        </Avatar>
                                        <span className="font-medium">
                                            {props.auth.user.name}
                                        </span>
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                            {props.streamers.map((item) => (
                                <DropdownMenuGroup>
                                    <DropdownMenuItem asChild>
                                        <Link
                                            className="block w-full"
                                            href={main(item.id)}
                                            as="button"
                                            prefetch
                                        >
                                            <Avatar className="size-7 overflow-hidden rounded-full">
                                                <AvatarImage
                                                    src={item.avatar}
                                                    alt={item.name}
                                                />
                                                <AvatarFallback className="rounded-full bg-neutral-200 text-xs text-black dark:bg-neutral-700 dark:text-white">
                                                    {item.name.substring(0, 1)}
                                                </AvatarFallback>
                                            </Avatar>
                                            <span className="font-medium">
                                                {item.name}
                                            </span>
                                        </Link>
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            ))}
                        </DropdownMenuContent>
                    </DropdownMenu>
                </SidebarMenuItem>
            </Collapsible>

            <Separator className="my-6 lg:hidden" />
            {navItems.map((item) => (
                <SidebarMenuButton
                    size="sm"
                    variant="outline"
                    asChild
                    className={cn('w-full justify-start', {
                        'bg-muted': isSameUrl(currentPath, item.href),
                    })}
                >
                    <Link href={item.href}>
                        {item.icon && <item.icon className="h-4 w-4" />}
                        <T
                            ns="streamerSidebar"
                            k={item.key}
                            loadingSkeleton={true}
                        />
                    </Link>
                </SidebarMenuButton>
            ))}
        </SidebarMenu>
    );
}
