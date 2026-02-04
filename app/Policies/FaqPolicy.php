<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Faq\FaqEntry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FaqPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyFaq);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FaqEntry $faqEntry): bool
    {
        return $user->can(Permission::ViewAnyFaq);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreateFaq);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FaqEntry $faqEntry): bool
    {
        return $user->can(Permission::UpdateAnyFaq);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FaqEntry $faqEntry): bool
    {
        return $user->can(Permission::DeleteAnyFaq);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FaqEntry $faqEntry): bool
    {
        return $user->can(Permission::RestoreAnyFaq);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FaqEntry $faqEntry): bool
    {
        return $user->can(Permission::ForceDeleteAnyFaq);
    }
}
