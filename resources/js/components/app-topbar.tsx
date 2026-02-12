import ClipVoteController from '@/actions/App/Http/Controllers/ClipVoteController';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { UserMenuContent } from '@/components/user-menu-content';
import { useInitials } from '@/hooks/use-initials';
import { cn } from '@/lib/utils';
import { home, login, manage_clips } from '@/routes';
import submitclip from '@/routes/submitclip';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronDown, LayoutGrid, ScanHeart, Send } from 'lucide-react';
import { lazy, Suspense, useEffect, useRef } from 'react';
import { useTranslation } from 'react-i18next';
import LogoFullDark from '/resources/images/svg/logo-full-dark.svg';
import LogoFullLight from '/resources/images/svg/logo-full-title.svg';

const TwitchPermissionsBanner = lazy(
    () => import('@/components/twitch-permissions-banner'),
);

// Navigation item keys for translation lookup
const navItemKeys = [
    {
        key: 'dashboard',
        href: manage_clips(),
        icon: LayoutGrid,
    },
    {
        key: 'submit_clips',
        href: submitclip.create(),
        icon: Send,
    },
    {
        key: 'evaluate_clips',
        href: ClipVoteController.create(),
        icon: ScanHeart,
    },
] as const;

export function AppTopbar() {
    const { t } = useTranslation('navigation');
    const page = usePage<SharedData>();
    const { auth } = page.props;
    const getInitials = useInitials();
    const searchInputRef = useRef<HTMLInputElement>(null);

    // Keyboard shortcut: Ctrl+K to focus search
    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInputRef.current?.focus();
            }
        };

        document.addEventListener('keydown', handleKeyDown);
        return () => document.removeEventListener('keydown', handleKeyDown);
    }, []);

    // Check if current URL matches href
    const isActive = (href: string | { url: string }) => {
        const hrefString = typeof href === 'string' ? href : href.url;
        return page.url === hrefString || page.url.startsWith(hrefString + '/');
    };

    const showTwitchPermissionsBanner = Boolean(
        page.flash?.showTwitchPermissionsPrompt,
    );

    return (
        <div className="sticky top-0 z-50 w-full">
            {showTwitchPermissionsBanner && (
                <Suspense fallback={null}>
                    <TwitchPermissionsBanner />
                </Suspense>
            )}

            <header className="w-full px-2 py-2">
                <div className="flex h-14 min-w-0 items-center gap-2 rounded-xl bg-background px-3 shadow-xl md:gap-4 md:px-4">
                    {/* Logo */}
                    <div className="flex shrink-0 items-center">
                        <Link
                            href={home()}
                            prefetch
                            className="flex items-center transition-opacity hover:opacity-80"
                        >
                            <img
                                src={LogoFullDark}
                                alt={t('logo_alt')}
                                className="hidden h-8 dark:block"
                            />
                            <img
                                src={LogoFullLight}
                                alt={t('logo_alt')}
                                className="block h-8 dark:hidden"
                            />
                        </Link>
                    </div>

                    {/* Spacer (nimmt Platz, damit Nav/User rechts bleiben) */}
                    <div className="min-w-0 flex-1" />

                    {/* Navigation Links */}
                    <nav className="flex shrink-0 items-center gap-1">
                        {navItemKeys.map((item) => (
                            <Link
                                key={item.key}
                                href={item.href}
                                className={cn(
                                    'flex items-center gap-2 rounded-lg px-2 py-2 text-sm font-medium transition-colors md:px-3 md:py-1.5',
                                    isActive(item.href)
                                        ? 'bg-sidebar-accent text-sidebar-accent-foreground'
                                        : 'text-sidebar-foreground/70 hover:bg-sidebar-accent/50 hover:text-sidebar-foreground',
                                )}
                                aria-label={t(item.key)}
                                title={t(item.key)}
                            >
                                {item.icon && (
                                    <item.icon className="size-4 shrink-0" />
                                )}
                                <span className="hidden lg:inline">
                                    {t(item.key)}
                                </span>
                            </Link>
                        ))}
                    </nav>

                    {/* User Dropdown */}
                    {auth.user !== null ? (
                        <div className="flex shrink-0 items-center">
                            <DropdownMenu modal={false}>
                                <DropdownMenuTrigger asChild>
                                    <Button
                                        variant="ghost"
                                        className="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-sidebar-accent/50"
                                    >
                                        <Avatar className="size-7 overflow-hidden rounded-full">
                                            <AvatarImage
                                                src={auth.user.avatar}
                                                alt={auth.user.name}
                                            />
                                            <AvatarFallback className="rounded-full bg-neutral-200 text-xs text-black dark:bg-neutral-700 dark:text-white">
                                                {getInitials(auth.user.name)}
                                            </AvatarFallback>
                                        </Avatar>

                                        <span className="hidden text-sm font-medium xl:inline">
                                            {auth.user.name}
                                        </span>

                                        <ChevronDown className="size-4 text-muted-foreground" />
                                    </Button>
                                </DropdownMenuTrigger>

                                <DropdownMenuContent
                                    className="w-72"
                                    align="end"
                                >
                                    <UserMenuContent user={auth.user} />
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    ) : (
                        <Link href={login()}>
                            <Button variant="outline" className="shrink-0">
                                <span className="hidden sm:inline">
                                    {t('login')}
                                </span>
                                <span className="sm:hidden">Login</span>
                            </Button>
                        </Link>
                    )}
                </div>
            </header>
        </div>
    );
}
