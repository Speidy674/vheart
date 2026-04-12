<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reports\StoreReportRequest;
use App\Models\Report;
use Filament\Facades\Filament;
use Illuminate\Http\JsonResponse;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request): JsonResponse
    {
        $report = Report::create(array_merge($request->validated(), ['user_id' => $request->user()->id]));

        DiscordAlert::to('moderation')->message('@here', [[
            'title' => 'New Report',
            'url' => Filament::getPanel('admin')->getResourceUrl($report, 'view'),
            'color' => '#e71d73',
        ]]);

        return new JsonResponse([
            'reportId' => (string) $report->id,
        ]);
    }
}
