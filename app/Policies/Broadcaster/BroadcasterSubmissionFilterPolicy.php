<?php

declare(strict_types=1);

namespace App\Policies\Broadcaster;

use App\Enums\Permission;
use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\BroadcasterSubmissionFilter;
use App\Models\User;

class BroadcasterSubmissionFilterPolicy
{
    public function viewAny(User $user, ?Broadcaster $broadcaster = null): bool
    {
        if ($user->id === $broadcaster?->id) {
            return true;
        }

        return $user->can(Permission::ViewAnyBroadcasterSubmissionFilter);
    }

    public function view(User $user, BroadcasterSubmissionFilter $broadcasterSubmissionFilter): bool
    {
        if ($user->id === $broadcasterSubmissionFilter->broadcaster_id) {
            return true;
        }

        return $user->can(Permission::ViewAnyBroadcasterSubmissionFilter);
    }

    public function create(User $user, ?Broadcaster $broadcaster = null): bool
    {
        if ($user->id === $broadcaster?->id) {
            return true;
        }

        return $user->can(Permission::CreateAnyBroadcasterSubmissionFilter);
    }

    public function update(User $user, BroadcasterSubmissionFilter $broadcasterSubmissionFilter): bool
    {
        if ($user->id === $broadcasterSubmissionFilter->broadcaster_id) {
            return true;
        }

        return $user->can(Permission::UpdateAnyBroadcasterSubmissionFilter);
    }

    public function delete(User $user, BroadcasterSubmissionFilter $broadcasterSubmissionFilter): bool
    {
        if ($user->id === $broadcasterSubmissionFilter->broadcaster_id) {
            return true;
        }

        return $user->can(Permission::DeleteAnyBroadcasterSubmissionFilter);
    }

    public function deleteAny(User $user, ?Broadcaster $broadcaster = null): bool
    {
        if ($user->id === $broadcaster?->id) {
            return true;
        }

        return $user->can(Permission::DeleteAnyBroadcaster);
    }

    public function restore(User $user, BroadcasterSubmissionFilter $broadcasterSubmissionFilter): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, BroadcasterSubmissionFilter $broadcasterSubmissionFilter): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
