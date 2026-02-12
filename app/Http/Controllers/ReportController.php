<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reports\StoreReportRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request): RedirectResponse
    {
        Report::create(array_merge($request->validated(), ['user_id' => $request->user()->id]));

        return back();
    }
}
