import { ClipPreview } from '@/components/clip-preview';
import { StreamerSection } from '@/components/sidebar/streamer-section';
import StaticSpaceBackground from '@/components/spacebackground';
import AppLayout from '@/layouts/app-layout';
import { clips as dashboardClips, main } from '@/routes/dashboard';
import { PublicClip, PublicUser, type BreadcrumbItem } from '@/types';
import { Head, InfiniteScroll, usePage } from '@inertiajs/react';

type PageProps = {
    selectedStreamer: PublicUser;
    clips?: {
        data: PublicClip[];
    };
};

export default function clips() {
    const { props } = usePage<PageProps>();
    console.log('Dashboard/clips', props);

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: main(props.selectedStreamer.id).url,
        },
        {
            title: 'Clips',
            href: dashboardClips(props.selectedStreamer.id).url,
        },
    ];

    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
            sidebarContent={<StreamerSection />}
        >
            <Head title={props.selectedStreamer.name + ' Dashboard Clips'} />
            <StaticSpaceBackground />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <InfiniteScroll data="clips" preserveUrl buffer={100}>
                    <div className="grid auto-rows-min gap-4 md:grid-cols-1">
                        {props.clips?.data?.map((clip) => (
                            <div
                                key={'clip' + clip.id}
                                className="relative max-h-32 rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-2 ring-1 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent dark:ring-0"
                            >
                                <div
                                    key={'clip' + clip.id}
                                    className="aspect-video h-full overflow-hidden rounded-md"
                                >
                                    <ClipPreview clip={clip} />
                                </div>
                            </div>
                        ))}
                    </div>
                </InfiniteScroll>
            </div>
        </AppLayout>
    );
}
