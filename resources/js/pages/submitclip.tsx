import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { Form, Head, Link, usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';

import { store } from '@/actions/App/Http/Controllers/ClipSubmitController';
import InputError from '@/components/input-error';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { TagSelect } from '@/components/ui/tag-select';
import { AlertCircle, CheckCircle2, Loader2 } from 'lucide-react';

import { TagResource } from '@/types';
import TwitchClipEmbed from '@/components/embeds/twitch-clip-embed';


type InertiaBaseProps = Record<string, unknown>;

interface PageProps extends InertiaBaseProps {
    auth: {
        permissions: Array<string>;
        user: {
            id: number;
            name: string;
            submission_count_today: number;
            daily_submission_limit: number;
        };
    };
    tags: TagResource[];
    submit_ok?: boolean;
    submit_message?: string;
}

export default function SubmitClipPage({ tags = [] }: { tags: TagResource[] }) {
    const { t } = useTranslation('sendinclip');
    const { props } = usePage<PageProps>();
    const { errors } = props;
    const user = props.auth?.user || null;

    const [isSubmitting] = useState(false);
    const [clipUrl, setClipUrl] = useState('');
    const [debouncedClipUrl, setDebouncedClipUrl] = useState('');

    const [error] = useState<string | null>(null);
    const [selectedTagIds, setSelectedTagIds] = useState<number[]>([]);

    useEffect(() => {
        const handler = setTimeout(() => {
            setDebouncedClipUrl(clipUrl);
        }, 250);

        return () => {
            clearTimeout(handler);
        };
    }, [clipUrl]);

    const previewErrors: string[] = [];

    const hasInput = debouncedClipUrl.trim().length > 0;

    const showErrors = false;

    const clipId = useMemo(() => {
        const clipMatch = debouncedClipUrl.match(
            /https?:\/\/(?:www|clips)?\.?(?:twitch\.tv\/)(?:embed\?clip=|[\w/]+\/clip\/)?([\w_-]+)/,
        );

        return clipMatch ? clipMatch[1] : null;
    }, [debouncedClipUrl]);

    const showLoading = hasInput && !clipId;
    const isTagSelectionValid = selectedTagIds.length >= 1;

    if (!user) {
        return (
            <AppHeaderLayout>
                <Head title={t('page_title')} />
                <div className="container mx-auto px-4 py-8">
                    <Card>
                        <CardHeader>
                            <CardTitle>{t('login.title')}</CardTitle>
                            <CardDescription>
                                {t('login.subtitle')}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Alert variant="destructive">
                                <AlertCircle className="h-4 w-4" />
                                <AlertDescription>
                                    {t('login.alert')}
                                </AlertDescription>
                            </Alert>
                        </CardContent>
                        <CardFooter>
                            <Link href="/login" className="w-full">
                                <Button className="w-full">
                                    {t('login.cta')}
                                </Button>
                            </Link>
                        </CardFooter>
                    </Card>
                </div>
            </AppHeaderLayout>
        );
    }

    return (
        <AppHeaderLayout>
            <Head title={t('page_title')} />

            <div className="container mx-auto px-4 py-8">
                <div className="mx-auto max-w-4xl">
                    {props.submit_ok && props.submit_message && (
                        <div className="mb-6">
                            <Alert variant="success">
                                <CheckCircle2 className="h-4 w-4" />
                                <AlertDescription>
                                    {props.submit_message}
                                </AlertDescription>
                            </Alert>
                        </div>
                    )}

                    {error && (
                        <div className="mb-6">
                            <Alert variant="destructive">
                                <AlertCircle className="h-4 w-4" />
                                <AlertDescription>{error}</AlertDescription>
                            </Alert>
                        </div>
                    )}

                    <div className="grid gap-8 lg:grid-cols-3">
                        <div className="space-y-6 lg:col-span-2">
                            <Card>
                                <CardHeader>
                                    <CardTitle>{t('preview.title')}</CardTitle>
                                </CardHeader>

                                <CardContent>
                                    <div className="space-y-4">
                                        <div className="aspect-video overflow-hidden rounded-lg bg-black">
                                            {clipId ? (
                                                <TwitchClipEmbed slug={clipId} />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center text-center text-muted-foreground">
                                                    {showLoading ? (
                                                        <div className="flex items-center justify-center gap-2">
                                                            <Loader2 className="h-5 w-5 animate-spin" />
                                                            <span>
                                                                {t(
                                                                    'preview.loading',
                                                                )}
                                                            </span>
                                                        </div>
                                                    ) : (
                                                        <p className="text-sm font-medium">
                                                            {t(
                                                                'preview.placeholder',
                                                            )}
                                                        </p>
                                                    )}
                                                </div>
                                            )}
                                        </div>

                                        {showErrors && (
                                            <Alert variant="destructive">
                                                <AlertCircle className="h-4 w-4" />
                                                <AlertDescription>
                                                    <ul className="list-inside list-disc space-y-1">
                                                        {previewErrors.map(
                                                            (m, idx) => (
                                                                <li key={idx}>
                                                                    {m}
                                                                </li>
                                                            ),
                                                        )}
                                                    </ul>
                                                </AlertDescription>
                                            </Alert>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardHeader>
                                    <CardTitle>{t('submit.title')}</CardTitle>
                                </CardHeader>

                                <CardContent>
                                    <Form
                                        action={store()}
                                        className="space-y-6"
                                        onSuccess={() => {
                                            setClipUrl('');
                                            setSelectedTagIds([]);
                                        }}
                                        noValidate
                                    >
                                        <div className="space-y-2">
                                            <Label htmlFor="clip_url">
                                                {t('submit.clip_url_label')}
                                            </Label>
                                            <Input
                                                id="clip_url"
                                                name="clip_url"
                                                placeholder={t(
                                                    'submit.clip_url_placeholder',
                                                )}
                                                value={clipUrl}
                                                onChange={(e) =>
                                                    setClipUrl(e.target.value)
                                                }
                                                onKeyDown={(e) => {
                                                    if (e.key === 'Enter')
                                                        e.preventDefault();
                                                }}
                                                disabled={isSubmitting}
                                                autoComplete="off"
                                                inputMode="url"
                                                type="url"
                                            />
                                            <InputError
                                                className="mt-2"
                                                message={errors.clip_url}
                                            />
                                        </div>

                                        <TagSelect
                                            tags={tags}
                                            label={t('submit.tags_label')}
                                            selectedIds={selectedTagIds}
                                            onChange={setSelectedTagIds}
                                            maxSelections={3}
                                            placeholder={t(
                                                'submit.tags_placeholder',
                                            )}
                                            filterPlaceholder={t(
                                                'submit.tags_filter_placeholder',
                                            )}
                                            noResultsText={t(
                                                'submit.tags_no_results',
                                            )}
                                            selectedCountText={(count, max) =>
                                                t(
                                                    'submit.tags_selected_count',
                                                    { count, max },
                                                )
                                            }
                                            maxErrorMessage={(max) =>
                                                t('submit.tags_max_error', {
                                                    max,
                                                })
                                            }
                                            removeLabel={(tag) =>
                                                t('submit.tags_remove_label', {
                                                    tag,
                                                })
                                            }
                                            errorMessage={errors.tags}
                                        />

                                        <Separator />

                                        <div className="flex items-center space-x-2">
                                            <Checkbox
                                                id="is_anonymous"
                                                name="is_anonymous"
                                            />
                                            <Label
                                                htmlFor="is_anonymous"
                                                className="cursor-pointer"
                                            >
                                                {t('submit.anonymous')}
                                                <span className="ml-1 text-xs text-muted-foreground">
                                                    {t('submit.anonymous_hint')}
                                                </span>
                                            </Label>
                                            <InputError
                                                className="mt-2"
                                                message={errors.is_anonymous}
                                            />
                                        </div>

                                        <Button
                                            type="submit"
                                            className="w-full"
                                            disabled={
                                                !clipId || !isTagSelectionValid
                                            }
                                        >
                                            {t('submit.cta')}
                                        </Button>
                                    </Form>
                                </CardContent>
                            </Card>
                        </div>

                        <div className="space-y-6">
                            <Card>
                                <CardHeader>
                                    <CardTitle className="text-sm">
                                        {t('rules.title')}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <ul className="space-y-2 text-sm">
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-primary" />
                                            <span>
                                                {t('rules.items.registered')}
                                            </span>
                                        </li>
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-primary" />
                                            <span>
                                                {t('rules.items.consent')}
                                            </span>
                                        </li>
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-primary" />
                                            <span>
                                                {t('rules.items.no_explicit')}
                                            </span>
                                        </li>
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-primary" />
                                            <span>
                                                {t('rules.items.tags_match')}
                                            </span>
                                        </li>
                                    </ul>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardHeader>
                                    <CardTitle className="text-sm">
                                        {t('tips.title')}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <ul className="space-y-2 text-sm">
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-blue-500" />
                                            <span>{t('tips.items.short')}</span>
                                        </li>
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-blue-500" />
                                            <span>
                                                {t('tips.items.quality')}
                                            </span>
                                        </li>
                                        <li className="flex items-start gap-2">
                                            <div className="mt-1 h-2 w-2 shrink-0 translate-y-0.5 rounded-full bg-blue-500" />
                                            <span>{t('tips.items.funny')}</span>
                                        </li>
                                    </ul>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </AppHeaderLayout>
    );
}
