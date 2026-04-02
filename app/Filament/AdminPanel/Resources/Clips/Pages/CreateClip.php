<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Clips\Pages;

use App\Filament\AdminPanel\Resources\Clips\ClipResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClip extends CreateRecord
{
    protected static string $resource = ClipResource::class;
}
