<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Broadcaster\BroadcasterConsentLog;
use App\Models\Clip\Compilation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RemoverUserDataJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly int $userId,
    ) {}

    public function handle(): void
    {
        $user = User::onlyTrashed()
            ->with([
                'broadcaster',
            ])
            ->find($this->userId);

        if (! $user) {
            return;
        }

        if ($broadcaster = $user->broadcaster) {
            DB::transaction(static function () use ($broadcaster) {
                $broadcaster->members()->delete();
                $broadcaster->filters()->delete();

                if ($broadcaster->consent?->isNotEmpty()) {
                    BroadcasterConsentLog::create([
                        'broadcaster_id' => $broadcaster->id,
                        'state' => [],
                        'change_reason' => 'User Deleted',
                        'changed_by' => 0,
                        'changed_at' => now(),
                    ]);
                }

                $broadcaster->forceDeleteQuietly();
            });
        }

        $user->broadcasterTeamMembers()->delete();
        $user->notifications()->delete();

        $user->updateQuietly([
            'name' => 'Deleted User',
            'email' => null,
            'email_verified_at' => null,
            'avatar_url' => null,
            'app_authentication_secret' => null,
            'app_authentication_recovery_codes' => null,
            'has_email_authentication' => false,
            'twitch_refresh_token' => null,
        ]);

        $hasReferences = $user->votes()->exists()
            || $user->reports()->exists()
            || $user->comments()->exists()
            || Compilation::where('user_id', $user->id)->exists();

        if (! $hasReferences) {
            // If there are no foreign key references we can safely force delete the user
            $user->forceDelete();
        }
    }
}
