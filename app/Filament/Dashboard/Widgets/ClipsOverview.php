<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\Clip;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClipsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Clips';

    protected function getStats(): array
    {
        $user = auth()->user();

        $totalClips = Clip::whereBroadcastBy($user)->count();
        $totalClipsToday = Clip::whereBroadcastBy($user)->where('created_at', '>=', now()->startOfDay())->count();
        $totalClipsThisWeek = Clip::whereBroadcastBy($user)->where('created_at', '>=', now()->startOfWeek())->count();
        $totalClipsThisMonth = Clip::whereBroadcastBy($user)->where('created_at', '>=', now()->startOfMonth())->count();

        $averageDuration = Clip::whereBroadcastBy($user)->avg('duration') ?? 0;
        $clipsLast30Days = Clip::whereBroadcastBy($user)->where('created_at', '>=', now()->subDays(30))->count();
        $averageClipsPerDay = $clipsLast30Days / 30;

        return [
            Stat::make('Total Clips Submitted', number_format($totalClips))
                ->icon(Heroicon::VideoCamera),
            Stat::make('Clips Submitted Today', number_format($totalClipsToday))
                ->icon(Heroicon::VideoCamera),
            Stat::make('Clips Submitted This Week', number_format($totalClipsThisWeek))
                ->icon(Heroicon::VideoCamera),
            Stat::make('Clips Submitted This Month', number_format($totalClipsThisMonth))
                ->icon(Heroicon::VideoCamera),

            Stat::make('Avg. Clip Duration', number_format($averageDuration).'s')
                ->icon(Heroicon::Clock),
            Stat::make('Avg. Daily Submissions (30 Days)', number_format($averageClipsPerDay, 2))
                ->icon(Heroicon::ChartBar),
        ];
    }
}
