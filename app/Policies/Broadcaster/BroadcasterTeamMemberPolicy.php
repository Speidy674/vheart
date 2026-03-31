<?php

declare(strict_types=1);

namespace App\Policies\Broadcaster;

use App\Enums\Permission;
use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\BroadcasterTeamMember;
use App\Models\User;

class BroadcasterTeamMemberPolicy
{
    public function viewAny(User $user, ?Broadcaster $broadcaster = null): bool
    {
        if ($user->id === $broadcaster?->id) {
            return true;
        }

        return $user->can(Permission::ViewAnyBroadcasterTeamMember);
    }

    public function view(User $user, BroadcasterTeamMember $broadcasterTeamMember): bool
    {
        if ($user->id === $broadcasterTeamMember->broadcaster_id) {
            return true;
        }

        return $user->can(Permission::ViewAnyBroadcasterTeamMember);
    }

    public function create(User $user, ?Broadcaster $broadcaster = null): bool
    {
        if ($user->id === $broadcaster?->id) {
            return true;
        }

        return $user->can(Permission::CreateAnyBroadcasterTeamMember);
    }

    public function update(User $user, BroadcasterTeamMember $broadcasterTeamMember): bool
    {
        if ($user->id === $broadcasterTeamMember->broadcaster_id) {
            return true;
        }

        return $user->can(Permission::UpdateAnyBroadcasterTeamMember);
    }

    public function delete(User $user, BroadcasterTeamMember $broadcasterTeamMember): bool
    {
        if ($user->id === $broadcasterTeamMember->broadcaster_id) {
            return true;
        }

        return $user->can(Permission::DeleteAnyBroadcasterTeamMember);
    }

    public function deleteAny(User $user, ?Broadcaster $broadcaster = null): bool
    {
        if ($user->id === $broadcaster?->id) {
            return true;
        }

        return $user->can(Permission::DeleteAnyBroadcaster);
    }

    public function restore(User $user, BroadcasterTeamMember $broadcasterTeamMember): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, BroadcasterTeamMember $broadcasterTeamMember): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
