<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Reports\ReportReason;
use App\Http\Requests\Reports\StoreReportRequest;
use App\Models\Report;
use App\Models\User;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class StoreReportAction
{
    public function __invoke(Model|StoreReportRequest $reportableOrStoreReportRequest, ?ReportReason $reason = null, ?string $description = null, ?User $user = null): Report
    {
        $usingFormRequest = $reportableOrStoreReportRequest instanceof StoreReportRequest;

        $report = Report::create([
            'user_id' => $usingFormRequest ? $reportableOrStoreReportRequest->user()->id : $user->getKey(),
            'reportable_type' => $usingFormRequest ? $reportableOrStoreReportRequest->input('reportable_type') : $reportableOrStoreReportRequest->getMorphClass(),
            'reportable_id' => $usingFormRequest ? $reportableOrStoreReportRequest->input('reportable_id') : $reportableOrStoreReportRequest->getKey(),
            'reason' => $usingFormRequest ? $reportableOrStoreReportRequest->enum('reason', ReportReason::class) : $reason,
            'description' => $usingFormRequest ? $reportableOrStoreReportRequest->input('description') : $description,
        ]);

        try {
            DiscordAlert::to('moderation')->message('<@&1494691682422226996>', [[
                'title' => 'New Report',
                'url' => Filament::getPanel('admin')->getResourceUrl($report, 'view'),
                'color' => '#e71d73',
            ]]);
        } catch (Exception $exception) {
            report($exception);
        }

        return $report;
    }
}
