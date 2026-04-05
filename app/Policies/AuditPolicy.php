<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Audit;
use App\Models\User;

class AuditPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyAudit);
    }

    public function view(User $user, Audit $audit): bool
    {
        return $user->can(Permission::ViewAnyAudit);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Audit $audit): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Audit $audit): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, Audit $audit): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
