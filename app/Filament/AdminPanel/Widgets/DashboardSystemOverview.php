<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Widgets;

use App\Enums\Filament\LucideIcon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardSystemOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'System';

    public static function canView(): bool
    {
        return auth()->user()->getRole()?->id === 0;
    }

    protected function getStats(): array
    {
        $failedJobs = DB::table('failed_jobs')->count();
        $currentJobs = DB::table('jobs')->count();

        return [
            Stat::make('Current Queue Jobs', number_format($currentJobs))
                ->icon(LucideIcon::Server),

            Stat::make('Failed Queue Jobs', number_format($failedJobs))
                ->icon($failedJobs > 0 ? LucideIcon::CircleX : LucideIcon::CircleCheck)
                ->color($failedJobs > 0 ? 'danger' : 'success'),
        ];
    }
}
