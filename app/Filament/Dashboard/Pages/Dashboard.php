<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function getNavigationLabel(): string
    {
        return __('dashboard/navigation.dashboard');
    }
}
