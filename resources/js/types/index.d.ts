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
    email: string | null;
    email_verified_at: string | null;
    avatar?: string;
    clip_permission?: boolean;
    rules: string[];
    app_authentication_secret?: string;
    app_authentication_recovery_codes?: string[];
    has_email_authentication: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface FaqEntryResource {
    id: number;
    title: string;
    body: string;
    order: number;
}

export interface RoleResource {
    id: number;
    name: string;
    description: string | null;
    weight: number;
    created_at: string;
    updated_at: string;
}
export type MinimalRoleResource = Pick<RoleResource, 'id' | 'name'>;

export interface TagResource {
    id: number;
    name: string;
}

export interface CategoryResource {
    id: number;
    name: string;
    art: {
        small: string;
        medium: string;
        large: string;
        raw: string;
    }
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

    broadcaster?: PublicUser;
    clipper?: PublicUser;
    submitter?: PublicUser;
    category?: CategoryResource;
    vod?: [id: number, offset: number];
    votes?: number;
    clip_duration: number;
    clipped_at: string;
    submitted_at: string;
}
