import { store } from '@/actions/App/Http/Controllers/ReportController';
import { ReportableType } from '@/components/reports/report-button';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Form, router, usePage } from '@inertiajs/react';
import { LucideCheck, LucideLoaderCircle } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';

export interface ReportReason {
    id: number;
    label: string;
}

export interface ReportModalProps {
    reportableId: number | string;
    reportableType: ReportableType;
    isOpen: boolean;
    onLoaded?: () => void;
    onClose: () => void;
}

/* Please do not use directly unless you know what you do lol, use ReportButton instead */
export default function ReportModal({
    reportableId,
    reportableType,
    isOpen,
    onLoaded,
    onClose,
}: ReportModalProps) {
    const { t } = useTranslation('reports');
    const [wasSuccessful, setWasSuccessful] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [hasFetched, setHasFetched] = useState(false);
    const isFetchingRef = useRef(false);

    const { reportOptions } = usePage<{
        reportOptions?: { reasons: ReportReason[] };
    }>().props;

    useEffect(() => {
        async function setLoading(state: boolean) {
            setIsLoading(state);
        }
        async function setFetched(state: boolean) {
            setHasFetched(state);
        }

        if (isOpen && !hasFetched && !isLoading && !isFetchingRef.current) {
            isFetchingRef.current = true;
            void setLoading(true);

            router.reload({
                only: ['reportOptions'],
                onFinish: () => {
                    void setLoading(false);
                    void setFetched(true);
                    isFetchingRef.current = false;
                    onLoaded?.();
                },
            });
        }

        if (!isOpen && hasFetched) {
            void setFetched(false);
        }
    }, [isOpen, hasFetched, isLoading, onLoaded]);

    const handleClose = () => {
        setWasSuccessful(false);
        onClose();
    };

    return (
        <Dialog open={isOpen} onOpenChange={(open) => !open && handleClose()}>
            <DialogContent className="sm:max-w-md">
                {wasSuccessful ? (
                    <div className="flex flex-col items-center justify-center space-y-4 py-6 text-center">
                        <div className="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                            <LucideCheck className="size-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div className="space-y-1">
                            <h3 className="text-lg font-semibold">
                                {t('modal.success.title', 'Report Submitted')}
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                {t('modal.success.message')}
                            </p>
                        </div>
                        <Button
                            onClick={handleClose}
                            className="w-full sm:w-auto"
                        >
                            {t('modal.success.ok', 'OK')}
                        </Button>
                    </div>
                ) : (
                    <>
                        <DialogHeader>
                            <DialogTitle>
                                {t('modal.title', {
                                    reportable: t(
                                        'reportable.' + reportableType,
                                    ),
                                })}
                            </DialogTitle>
                            <DialogDescription>
                                {t('modal.subtitle')}
                            </DialogDescription>
                        </DialogHeader>
                        <Form
                            action={store()}
                            method="post"
                            className="relative space-y-4"
                            disableWhileProcessing
                            onSuccess={() => setWasSuccessful(true)}
                            options={{
                                preserveScroll: true,
                                preserveState: true,
                                preserveUrl: true,
                                replace: true,
                                only: ['flash', 'errors'],
                            }}
                        >
                            {({ errors, processing }) => (
                                <div className="relative space-y-4">
                                    {(processing || isLoading) && (
                                        <div className="absolute inset-0 z-10 flex items-center justify-center bg-background/50 backdrop-blur-[1px]">
                                            <LucideLoaderCircle className="size-8 animate-spin text-primary" />
                                        </div>
                                    )}

                                    <input
                                        type="hidden"
                                        name="reportable_id"
                                        defaultValue={reportableId}
                                    />
                                    <input
                                        type="hidden"
                                        name="reportable_type"
                                        defaultValue={reportableType}
                                    />

                                    <div>
                                        <label className="text-sm leading-none font-medium">
                                            {t('modal.inputs.reason.label')}
                                        </label>
                                        <select
                                            name="reason"
                                            required
                                            className="mt-2 flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:border-gray-700 dark:bg-gray-800"
                                        >
                                            {(reportOptions?.reasons ?? []).map(
                                                (reason) => (
                                                    <option
                                                        key={reason.id}
                                                        value={reason.id}
                                                    >
                                                        {reason.label}
                                                    </option>
                                                ),
                                            )}
                                        </select>
                                        {errors.reason && (
                                            <p className="text-xs font-medium text-destructive">
                                                {errors.reason}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="text-sm leading-none font-medium">
                                            {t(
                                                'modal.inputs.description.label',
                                            )}
                                        </label>
                                        <textarea
                                            name="description"
                                            rows={4}
                                            placeholder={t(
                                                'modal.inputs.description.placeholder',
                                            )}
                                            className="mt-2 flex min-h-20 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:border-gray-700 dark:bg-gray-800"
                                        />
                                        {errors.description && (
                                            <p className="text-xs font-medium text-destructive">
                                                {errors.description}
                                            </p>
                                        )}
                                    </div>

                                    <div className="flex justify-end gap-3 pt-2">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            onClick={onClose}
                                            disabled={processing}
                                        >
                                            {t('modal.inputs.cancel')}
                                        </Button>
                                        <Button
                                            type="submit"
                                            variant="destructive"
                                            disabled={
                                                processing || isLoading
                                            }
                                        >
                                            {t('modal.inputs.submit')}
                                        </Button>
                                    </div>
                                </div>
                            )}
                        </Form>
                    </>
                )}
            </DialogContent>
        </Dialog>
    );
}
