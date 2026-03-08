import HeadingSmall from '@/components/heading-small';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit, update } from '@/routes/permissions';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Permissions',
        href: edit().url,
    },
];

export default function Permissions() {
    const { t } = useTranslation('settings');
    const { auth } = usePage<SharedData>().props;
    const [getBroadcasterConsent, setBroadcasterConsent] = useState(
        auth.user?.broadcaster?.consent,
    );
    const [isUpdating, setIsUpdating] = useState(false);

    const handleChange = (
        broadcasterConsent: number,
        checked: boolean | 'indeterminate',
    ) => {
        setIsUpdating(true);
        router.patch(
            update().url,
            { broadcasterConsent: broadcasterConsent, state: checked === true },
            {
                preserveScroll: true,
                onError: () => setIsUpdating(false),
                onFinish: () => {
                    setIsUpdating(false);
                    if (checked === true) {
                        setBroadcasterConsent([
                            ...getBroadcasterConsent,
                            broadcasterConsent,
                        ]);
                    } else {
                        setBroadcasterConsent(
                            getBroadcasterConsent?.filter(
                                (consent) => consent != broadcasterConsent,
                            ),
                        );
                    }
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs} sidebarVariant="personal_settings">
            <Head title={t('permissions.title')} />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title={t('permissions.title')}
                        description={t('permissions.description')}
                    />

                    <div className="space-y-4 rounded-lg border border-border/60 p-4">
                        <div className="flex items-start justify-between gap-4">
                            <div className="space-y-1">
                                <p className="text-sm font-medium">
                                    {t('permissions.clip_title')}
                                </p>
                                <p className="text-sm text-muted-foreground">
                                    {t('permissions.clip_description')}
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-2">
                            <Checkbox
                                id="consent_compilations"
                                checked={getBroadcasterConsent?.includes(0)}
                                onCheckedChange={(checked) =>
                                    handleChange(0, checked)
                                }
                                disabled={isUpdating}
                            />
                            <Label htmlFor="consent_compilations">
                                Compilations
                            </Label>

                            <Checkbox
                                id="consent_shorts"
                                checked={getBroadcasterConsent?.includes(1)}
                                onCheckedChange={(checked) =>
                                    handleChange(1, checked)
                                }
                                disabled={isUpdating}
                            />
                            <Label htmlFor="consent_shorts">Shorts</Label>
                        </div>
                        <p className="text-sm text-muted-foreground">
                            {t('permissions.clip_disclaimer')}
                        </p>
                    </div>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
