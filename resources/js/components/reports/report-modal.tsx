import { store } from '@/actions/App/Http/Controllers/ReportController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Form } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import {
    LucideCheck,
    LucideLoaderCircle,
} from 'lucide-react';
import { ReportableType } from '@/components/reports/report-button';

export interface ReportReason {
    id: number;
    labelKey: string;
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

    useEffect(() => {
        onLoaded?.();
    }, [onLoaded]);

    const handleClose = () => {
        setWasSuccessful(false);
        onClose();
    };

    // hardcoded for now, can fetch them later if we need it
    const reasons: ReportReason[] = [
        { id: 0, labelKey: 'enums.report-reason.other' },
        { id: 1, labelKey: 'enums.report-reason.spam' },
        { id: 2, labelKey: 'enums.report-reason.harassment' },
        { id: 3, labelKey: 'enums.report-reason.hate_speech' },
        { id: 4, labelKey: 'enums.report-reason.ai_content' },
    ];

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
                                    reportable: t('reportable.' + reportableType),
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
                                <div className="relative space-y-2">
                                    {processing && (
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

                                    <div className="space-y-2">
                                        <label className="text-sm leading-none font-medium">
                                            {t('modal.inputs.reason.label')}
                                        </label>
                                        <select
                                            name="reason"
                                            required
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:border-gray-700 dark:bg-gray-800"
                                        >
                                            {reasons.map((reason) => (
                                                <option
                                                    key={reason.id}
                                                    value={reason.id}
                                                >
                                                    {t(reason.labelKey)}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.reason && (
                                            <p className="text-xs font-medium text-destructive">
                                                {errors.reason}
                                            </p>
                                        )}
                                    </div>

                                    <div className="space-y-2">
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
                                            className="flex min-h-20 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:border-gray-700 dark:bg-gray-800"
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
                                            disabled={processing}
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
