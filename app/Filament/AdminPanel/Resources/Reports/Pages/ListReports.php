<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Reports\Pages;

use App\Filament\AdminPanel\Resources\Reports\ReportResource;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;
}
