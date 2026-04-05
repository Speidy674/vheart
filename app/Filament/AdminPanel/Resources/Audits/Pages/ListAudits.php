<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Audits\Pages;

use App\Filament\AdminPanel\Resources\Audits\AuditResource;
use Filament\Resources\Pages\ListRecords;

class ListAudits extends ListRecords
{
    protected static string $resource = AuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
