<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages;

use App\Enums\Filament\LucideIcon;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = LucideIcon::House;

    public static function getNavigationLabel(): string
    {
        return __('dashboard/navigation.dashboard');
    }
}
