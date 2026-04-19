<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Broadcaster\Broadcaster;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GenerateVotingQueueAction
{
    private const int QUEUE_SIZE = 20;

    public function execute(Authenticatable $user): array
    {
        $eligibleClips = static fn (HasMany|Builder $query): HasMany|Builder => $query->whereEligibleForVoting($user);

        $broadcasters = Broadcaster::whereHas('clips', $eligibleClips)
            ->inRandomOrder()
            ->limit(self::QUEUE_SIZE)
            ->with(['clips' => fn (HasMany $q): HasMany => $eligibleClips($q)->select('id', 'broadcaster_id')->inRandomOrder()->limit(1)])
            ->get(['id']);

        return $broadcasters
            ->pluck('clips')
            ->flatten()
            ->filter()
            ->pluck('id')
            ->shuffle()
            ->toArray();
    }

    private function eligibleClips(Authenticatable $user): Closure
    {
        return static fn (HasMany|Builder $query): HasMany|Builder => $query->whereEligibleForVoting($user);
    }
}
