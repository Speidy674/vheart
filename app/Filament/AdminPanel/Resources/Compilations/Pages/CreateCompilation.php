<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Compilations\Pages;

use App\Filament\AdminPanel\Resources\Compilations\CompilationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompilation extends CreateRecord
{
    protected static string $resource = CompilationResource::class;
}
