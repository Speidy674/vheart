<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reports\StoreReportRequest;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request): JsonResponse
    {
        $report = Report::create(array_merge($request->validated(), ['user_id' => $request->user()->id]));

        return new JsonResponse([
            'reportId' => (string) $report->id,
        ]);
    }
}
