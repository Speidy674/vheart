import SpaceBackground from '@/components/spacebackground';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import cat from '/resources/images/png/cat.png';
import { Head } from '@inertiajs/react';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
interface TeamMember {
    name: string;
    avatar?: string;
}

interface TeamRole {
    name: string;
    members: TeamMember[];
}

interface TeamPageProps {
    roles: TeamRole[];
}

function getInitials(name: string): string {
    const cleaned = String(name).trim();
    if (!cleaned) return '?';
    const parts = cleaned.split(/\s+/);
    const first = parts[0]?.[0] || '';
    const second = parts[1]?.[0] || parts[0]?.[1] || '';
    return (first + second).toUpperCase() || '?';
}

function TeamMemberCard({ member }: { member: TeamMember }) {
    const { name, avatar } = member;

    return (
            <div
                className={cn(
                    'group relative flex items-center gap-4 rounded-2xl border p-4 transition-all duration-300',
                    'border-white bg-white/95 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl',
                    'dark:border-white/10 dark:bg-zinc-900/40 dark:shadow-2xl dark:shadow-purple-900/20',
                    'group-hover:shadow-purple-500/10 hover:border-purple-500/30',
                )}
            >
                <div className="relative">
                    <Avatar className="h-14 w-14 border-2 border-white shadow-sm dark:border-white/10">
                        <AvatarImage
                            src={avatar?.trim() || cat}
                            alt={name}
                            className="object-cover"
                            loading="lazy"
                        />
                        <AvatarFallback className="bg-purple-100 font-bold text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                            {getInitials(name)}
                        </AvatarFallback>
                    </Avatar>
                </div>

                <div className="min-w-0 flex-1">
                    <p className="text-lg font-bold tracking-tight text-zinc-950 transition-colors group-hover:text-purple-600 dark:text-white dark:group-hover:text-purple-400">
                        {name}
                    </p>
                </div>
            </div>
    );
}

function RoleSection({ role }: { role: TeamRole }) {
    const { t } = useTranslation('team');
    const { name, members } = role;

    return (
            <section className="space-y-8">
                <div className="flex items-center gap-4">
                    <Badge
                        variant="outline"
                        className={cn(
                            'border-2 px-6 py-2 text-sm font-bold backdrop-blur-md',
                            'border-purple-100 bg-white text-purple-900 shadow-sm',
                            'dark:border-purple-500/30 dark:bg-zinc-900/60 dark:text-purple-300',
                        )}
                    >
                        {name}
                    </Badge>
                    <div className="h-[2px] flex-1 bg-gradient-to-r from-purple-100 via-transparent to-transparent dark:from-white/10" />
                    <span className="font-mono text-[10px] font-bold tracking-[0.2em] text-zinc-400 uppercase dark:text-white/30">
                        {members.length}{' '}
                        {members.length === 1 ? t('member') : t('members')}
                    </span>
                </div>

                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {members.map((member, index) => (
                        <TeamMemberCard
                            key={`${name}-${member.name}-${index}`}
                            member={member}
                        />
                    ))}
                </div>
            </section>
    );
}

export default function TeamPage({ roles = [] }: TeamPageProps) {
    const { t } = useTranslation('team');

    const totalMembers = useMemo(
        () =>
            roles.reduce(
                (total, role) => total + (role.members?.length || 0),
                0,
            ),
        [roles],
    );

    return (
        <AppHeaderLayout>
            <Head title={t('page_title')} />
            <div className="relative min-h-screen w-full overflow-hidden bg-[#F8FAFF] dark:bg-[#0a0a1a]">
                <SpaceBackground />

                <div className="relative z-10 container mx-auto px-4 py-16 sm:py-24">
                    <header className="mb-24 text-center">
                        <h1 className="mb-6 text-6xl font-black tracking-tighter sm:text-7xl md:text-8xl">
                            <span
                                className={cn(
                                    'bg-gradient-to-b bg-clip-text text-transparent',
                                    'from-zinc-950 via-zinc-800 to-purple-600',
                                    'dark:from-white dark:via-white dark:to-purple-500/50',
                                )}
                            >
                                {t('our_team')}
                            </span>
                        </h1>
                        <div className="mx-auto h-1.5 w-24 rounded-full bg-gradient-to-r from-purple-500 to-cyan-500" />
                        <p className="mt-8 text-sm font-bold tracking-[0.3em] text-purple-600 uppercase dark:text-cyan-400">
                            {t('total_members', { count: totalMembers })}
                        </p>
                    </header>
                    <main className="mx-auto max-w-7xl space-y-32">
                        {roles.length > 0 ? (
                            roles.map((role) => (
                                <RoleSection key={role.name} role={role} />
                            ))
                        ) : (
                            <div className="flex h-64 flex-col items-center justify-center rounded-3xl border-2 border-dashed border-purple-100 bg-white/50 backdrop-blur-xl dark:border-white/5 dark:bg-white/5">
                                <h3 className="text-xl font-bold tracking-widest text-zinc-400 uppercase dark:text-white/40">
                                    {t('no_team_data')}
                                </h3>
                                <p className="mt-2 text-sm text-zinc-500 dark:text-white/30">
                                    {t('no_team_description')}
                                </p>
                            </div>
                        )}
                    </main>
                </div>
            </div>
        </AppHeaderLayout>
    );
}
