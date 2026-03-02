<?php

declare(strict_types=1);

namespace App\Events\Admin\Compilations;

use App\Enums\Clips\CompilationClipStatus;
use App\Models\Clip;
use App\Models\Clip\Compilation;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event is dispatched if someone has updated the status of a clip in a compilation
 */
class CompilationClipStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Compilation $compilation,
        public User $user,
        public Clip $clip,
        public CompilationClipStatus $oldStatus,
        public CompilationClipStatus $newStatus,
    ) {}
}
