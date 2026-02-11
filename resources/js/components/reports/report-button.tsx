import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Loader2, LucideFlag } from 'lucide-react';
import { lazy, Suspense, useState } from 'react';
import { useTranslation } from 'react-i18next';

const ReportModal = lazy(() => import('@/components/reports/report-modal'));

export type ReportableType = 'user' | 'clip';
export interface ReportableItem {
    id: number;
    type: ReportableType;
    label?: string;
}

export default function ReportButton({ items, disabled }: { items: ReportableItem[], disabled?: boolean }) {
    const [selectedItem, setSelectedItem] = useState<ReportableItem | null>(
        null,
    );
    const [isLoading, setIsLoading] = useState(false);
    const { t, ready } = useTranslation('reports', {
        useSuspense: false,
    });

    const handleReportClick = (item: ReportableItem) => {
        setIsLoading(true);
        setSelectedItem(item);
    };

    const handleClose = () => {
        setSelectedItem(null);
        setIsLoading(false);
    };

    const ReportIcon = () => {
        if (isLoading || !ready) {
            return (
                <Loader2 className="size-6 animate-spin text-destructive" />
            );
        }
        return (
            <LucideFlag className="size-6 text-destructive/80 hover:text-destructive" />
        );
    };

    if (items.length === 0 && !disabled) return null;

    if(!ready || disabled) {
        return (
            <Button
                variant="ghost"
                size="icon"
                disabled={true}
                className="size-9 rounded-full bg-black ring-1 ring-white/10 transition-transform duration-150 ease-out hover:bg-black active:scale-95 disabled:opacity-40 sm:size-11 sm:hover:scale-110"
            >
                <ReportIcon />
            </Button>
        );
    }

    return (
        <>
            {items.length > 1 ? (
                <DropdownMenu
                    modal={false}
                    onOpenChange={(open) => {
                        // the user likely wants to interact with us, preload modal
                        if (open) import('@/components/reports/report-modal');
                    }}
                >
                    <DropdownMenuTrigger disabled={!ready} asChild>
                        <Button
                            variant="ghost"
                            size="icon"
                            disabled={isLoading}
                            className="size-9 rounded-full bg-black ring-1 ring-white/10 transition-transform duration-150 ease-out hover:bg-black active:scale-95 disabled:opacity-40 sm:size-11 sm:hover:scale-110"
                        >
                            <ReportIcon />
                            <span className="sr-only">Report options</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        {items.map((item) => (
                            <DropdownMenuItem
                                variant="destructive"
                                key={`${item.type}-${item.id}`}
                                onClick={() => handleReportClick(item)}
                            >
                                {t('modal.button', {
                                    reportable: t(
                                        'reportable.' + item.type,
                                        item.label || item.type,
                                    ),
                                })}
                            </DropdownMenuItem>
                        ))}
                    </DropdownMenuContent>
                </DropdownMenu>
            ) : (
                <Button
                    variant="ghost"
                    size="icon"
                    disabled={isLoading || !ready}
                    className="size-9 rounded-full bg-black ring-1 ring-white/10 transition-transform duration-150 ease-out hover:bg-black active:scale-95 disabled:opacity-40 sm:size-11 sm:hover:scale-110"
                    onClick={() => handleReportClick(items[0])}
                >
                    <ReportIcon />
                    <span className="sr-only">
                        {t('modal.button', {
                            reportable: t(
                                'reportable.' + items[0]?.type,
                                items[0]?.label || items[0]?.type,
                            ),
                        })}
                    </span>
                </Button>
            )}

            {selectedItem && (
                <Suspense fallback={null}>
                    <ReportModal
                        isOpen={!!selectedItem}
                        reportableId={selectedItem.id}
                        reportableType={selectedItem.type}
                        onClose={handleClose}
                        onLoaded={() => setIsLoading(false)}
                    />
                </Suspense>
            )}
        </>
    );
}
