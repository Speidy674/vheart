import { type BreadcrumbItem } from '@/types';
import { AppTopbar } from './app-topbar';

interface AppHeaderProps {
    breadcrumbs?: BreadcrumbItem[];
    isIsland?: boolean;
}

export function AppHeader({ isIsland }: AppHeaderProps) {
    return <AppTopbar isIsland={isIsland} />;
}
