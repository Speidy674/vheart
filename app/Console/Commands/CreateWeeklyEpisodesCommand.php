<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Clips\CompilationStatus;
use App\Enums\Clips\CompilationType;
use App\Models\Clip\Compilation;
use Carbon\Constants\UnitValue;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Signature('episodes:create-weekly')]
#[Description('Create episode compilations for current and next week if not exists')]
class CreateWeeklyEpisodesCommand extends Command
{
    public function handle(): void
    {
        $baseCount = Compilation::query()
            ->withTrashed()
            ->where('title', 'like', 'Episode %')
            ->where('type', CompilationType::LongVideo)
            ->count();

        $weeks = [
            [Carbon::now()->startOfWeek()->next(UnitValue::FRIDAY), $baseCount + 1],
            [Carbon::now()->addWeek()->startOfWeek()->next(UnitValue::FRIDAY), $baseCount + 2],
        ];

        foreach ($weeks as [$friday, $count]) {
            $this->createEpisodeForWeek($friday, $count);
        }
    }

    private function createEpisodeForWeek(Carbon $friday, int $count): void
    {
        $date = $friday->format('d.m.Y');
        $title = "Episode $count ($date)";

        $exists = Compilation::query()
            ->withTrashed()
            ->where('title', 'like', "%($date)")->exists();

        if ($exists) {
            $this->line("Skipping for $date");

            return;
        }

        Compilation::create([
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => CompilationStatus::Planned,
            'type' => CompilationType::LongVideo,
            'user_id' => 0,
        ]);

        Log::info("Episode created: {$title}");
        $this->info("Created: {$title}");
    }
}
