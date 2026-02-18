import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

import { manage_clips } from '@/routes';

export default function ManageClips() {
    const { t } = useTranslation('dashboard');

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t('nav.manage_clips'),
            href: manage_clips().url,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs} sidebarVariant="creator_dashboard">
            <Head title={t('nav.manage_clips')} />
        </AppLayout>
    );
}
