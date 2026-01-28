import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    clip_permission?: boolean;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Game {
    id: number;
    title: string;
    box_art: string;
}

export interface PublicUser {
    id: number;
    name: string;
    avatar: string;
}

/* PublicClipResource */
export interface PublicClip {
    id: number;
    slug: string;
    title: string;
    thumbnail_url: string;
    clip_url: string;

    broadcaster?: PublicUser,
    clipper?: PublicUser,
    submitter?: PublicUser,
    game?: Game
    vod?: [
        id: number,
        offset: number
    ]
    votes?: number,
    clip_duration: number;
    clipped_at: string;
    submitted_at: string;
}
