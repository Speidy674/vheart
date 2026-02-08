import { type BreadcrumbItem } from '@/types';
import { useTranslation } from 'react-i18next';

import AppLayout from '@/layouts/app-layout';
import { manage_permissions } from '@/routes';

export default function ManagePermisssions() {
    const { t } = useTranslation('dashboard');

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t('nav.permissions'),
            href: manage_permissions().url,
        },
    ];
    return (
        <AppLayout breadcrumbs={breadcrumbs} sidebarVariant="creator_dashboard">
            <div className="p-6" />
        </AppLayout>
    );
}
