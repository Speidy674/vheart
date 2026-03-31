<?php

declare(strict_types=1);

namespace App\Policies\Broadcaster;

use App\Enums\Permission;
use App\Models\Broadcaster\BroadcasterConsentLog;
use App\Models\User;

class BroadcasterConsentLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyBroadcasterConsentLog);
    }

    public function view(User $user, BroadcasterConsentLog $broadcasterConsentLog): bool
    {
        return $user->can(Permission::ViewAnyBroadcasterConsentLog);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, BroadcasterConsentLog $broadcasterConsentLog): bool
    {
        return false;
    }

    public function delete(User $user, BroadcasterConsentLog $broadcasterConsentLog): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BroadcasterConsentLog $broadcasterConsentLog): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, BroadcasterConsentLog $broadcasterConsentLog): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
