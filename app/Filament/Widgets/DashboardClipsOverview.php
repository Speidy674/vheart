<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\Permission;
use App\Models\Clip;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardClipsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Clips';

    public static function canView(): bool
    {
        return auth()->user()->can(Permission::ViewAnyClip);
    }

    protected function getStats(): array
    {
        $totalClips = Clip::count();
        $totalClipsToday = Clip::where('created_at', '>=', now()->startOfDay())->count();
        $totalClipsThisWeek = Clip::where('created_at', '>=', now()->startOfWeek())->count();
        $totalClipsThisMonth = Clip::where('created_at', '>=', now()->startOfMonth())->count();

        return [
            Stat::make('Total Clips Submitted', number_format($totalClips))
                ->icon(Heroicon::VideoCamera),
            Stat::make('Clips Submitted Today', number_format($totalClipsToday))
                ->icon(Heroicon::VideoCamera),
            Stat::make('Clips Submitted This Week', number_format($totalClipsThisWeek))
                ->icon(Heroicon::VideoCamera),
            Stat::make('Clips Submitted This Month', number_format($totalClipsThisMonth))
                ->icon(Heroicon::VideoCamera),
        ];
    }
}
