<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Reports\ReportStatus;
use App\Models\Report;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('reports:release-stale {--hours=48 : Hours before a claimed report is released} {--dry-run : Preview without making changes}')]
#[Description('Release reports claimed for more than the defined hours back to pending')]
class ReleaseStaleReportsCommand extends Command
{
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');
        $count = 0;

        Report::query()
            ->where('status', ReportStatus::InReview)
            ->where('claimed_at', '<=', now()->subHours($hours))
            ->each(function (Report $report) use (&$count, $dryRun): void {
                if (! $dryRun) {
                    $report->update([
                        'status' => ReportStatus::Pending,
                        'claimed_by' => null,
                        'claimed_at' => null,
                    ]);
                }
                $count++;
            });

        $this->info(($dryRun ? 'Would release' : 'Released')." {$count} stale report(s) older than {$hours}h.");

        return self::SUCCESS;
    }
}
