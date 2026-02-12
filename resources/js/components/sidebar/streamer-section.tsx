import { usePage } from '@inertiajs/react';
import { Check, ChevronDown } from 'lucide-react';
import { useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';

import type { SharedData } from '@/types';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';

type Streamer = {
    id: number | string;
    name: string;
    avatar: string | null;
};

type PageProps = SharedData & {
    streamers?: Streamer[];
};

export function StreamerSection() {
    const { t } = useTranslation('navigation');

    const page = usePage<PageProps>();
    const { auth, streamers: streamersFromProps } = page.props;

    const me = useMemo<Streamer>(
        () => ({
            id: auth.user.id,
            name: auth.user.name,
            avatar: auth.user.avatar ?? null,
        }),
        [auth.user.id, auth.user.name, auth.user.avatar],
    );

    const streamers = useMemo<Streamer[]>(() => {
        const provided = Array.isArray(streamersFromProps)
            ? streamersFromProps
            : [];
        return provided.length > 0 ? provided : [me];
    }, [streamersFromProps, me]);

    const [activeStreamer, setActiveStreamer] = useState<Streamer>(() => {
        return streamers.find((s) => s.id === me.id) ?? streamers[0];
    });

    return (
        <SidebarMenuItem>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <SidebarMenuButton className="hover:bg-transparent">
                        <Avatar className="size-4">
                            <AvatarImage
                                src={activeStreamer.avatar ?? undefined}
                            />
                            <AvatarFallback>
                                {activeStreamer.name.slice(0, 1)}
                            </AvatarFallback>
                        </Avatar>

                        <span className="truncate font-medium group-data-[collapsible=icon]:hidden">
                            {activeStreamer.name}
                        </span>

                        <ChevronDown className="ml-auto size-4 shrink-0 opacity-80" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>

                <DropdownMenuContent
                    align="start"
                    className="w-[--radix-dropdown-menu-trigger-width] min-w-56"
                >
                    <div className="px-2 py-1.5 text-xs text-muted-foreground">
                        {t('streamer')}
                    </div>

                    {streamers.map((streamer) => {
                        const isActive = streamer.id === activeStreamer.id;

                        return (
                            <DropdownMenuItem
                                key={String(streamer.id)}
                                onSelect={(e) => {
                                    e.preventDefault();
                                    setActiveStreamer(streamer);
                                }}
                                className={isActive ? 'font-medium' : ''}
                            >
                                <div className="flex w-full items-center gap-2">
                                    <Avatar className="size-4">
                                        <AvatarImage
                                            src={streamer.avatar ?? undefined}
                                        />
                                        <AvatarFallback>
                                            {streamer.name.slice(0, 1)}
                                        </AvatarFallback>
                                    </Avatar>

                                    <span className="truncate">
                                        {streamer.name}
                                    </span>

                                    {isActive && (
                                        <Check className="ml-auto size-3 text-muted-foreground" />
                                    )}
                                </div>
                            </DropdownMenuItem>
                        );
                    })}
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    );
}
