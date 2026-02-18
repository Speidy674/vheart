import { SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { main } from '@/routes/dashboard';
import { DashboardData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronDown } from 'lucide-react';
import { useTranslation } from 'react-i18next';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

export function StreamerSection() {
    const { t } = useTranslation('navigation');
    const { props } = usePage<DashboardData>();

    return (
        <SidebarMenuItem>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <SidebarMenuButton className="hover:cursor-pointer hover:bg-transparent focus:bg-transparent active:bg-transparent data-[state=open]:bg-transparent data-[state=open]:hover:bg-transparent">
                        <Avatar className="size-7 overflow-hidden rounded-full">
                            <AvatarImage
                                src={props.selectedStreamer.avatar}
                                alt={props.selectedStreamer.name}
                            />
                            <AvatarFallback className="rounded-full bg-neutral-200 text-xs text-black dark:bg-neutral-700 dark:text-white">
                                {props.selectedStreamer.name.substring(0, 1)}
                            </AvatarFallback>
                        </Avatar>
                        <span className="font-medium">
                            {props.selectedStreamer.name}
                        </span>
                        <ChevronDown className="ml-auto size-4 shrink-0 opacity-80" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    className="w-[--radix-dropdown-menu-trigger-width] min-w-56"
                    align="start"
                >
                    <DropdownMenuGroup
                        key={'streamerSelect' + props.auth.user.id}
                    >
                        <div className="px-2 py-1.5 text-xs text-muted-foreground">
                            {t('streamer')}
                        </div>
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
                                        {props.auth.user.name.substring(0, 1)}
                                    </AvatarFallback>
                                </Avatar>
                                <span className="font-medium">
                                    {props.auth.user.name}
                                </span>
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    {props.streamers.map((item) => (
                        <DropdownMenuGroup key={'streamerSelect' + item.id}>
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
    );
}
