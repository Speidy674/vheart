import { AppTopbar } from './app-topbar';

interface AppHeaderProps {
    isIsland?: boolean;
}

export function AppHeader({ isIsland }: AppHeaderProps) {
    return <AppTopbar isIsland={isIsland} />;
}
