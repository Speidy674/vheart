<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionSeeder extends Seeder
{
    protected array $adminPermissionsBlacklist = [
        Permission::ForceDeleteAnyCompilation,
        Permission::ForceDeleteAnyCategory,
        Permission::ForceDeleteAnyFaqEntry,
        Permission::DeleteAnyComment,
        Permission::ForceDeleteAnyClip,
        Permission::ForceDeleteAnyBroadcaster,
        Permission::DeleteAnyRemovalRequest,
        Permission::BypassConsentCheck,
    ];

    protected array $communityManagerPermissions = [
        Permission::JuryVote,
    ];

    protected array $moderatorPermissions = [
        Permission::ViewAnyUser,
        Permission::UpdateAnyUser,
        Permission::DeleteAnyUser,
        Permission::RestoreAnyUser,
        Permission::ViewAnyReport,
        Permission::ViewReport,
        Permission::CreateReport,
        Permission::UpdateAnyReport,
        Permission::DeleteAnyReport,
        Permission::RestoreAnyReport,
        Permission::ViewAnyCompilation,
        Permission::ViewCompilation,
        Permission::ViewAnyCategory,
        Permission::ViewCategory,
        Permission::UpdateAnyCategory,
        Permission::ViewAnyFaqEntry,
        Permission::ViewFaqEntry,
        Permission::ViewAnyComment,
        Permission::CreateComment,
        Permission::ViewAnyTag,
        Permission::ViewTag,
        Permission::ViewAnyClip,
        Permission::ViewClip,
        Permission::CreateClip,
        Permission::UpdateAnyClip,
        Permission::DeleteAnyClip,
        Permission::RestoreAnyClip,
        Permission::ViewAnyBroadcaster,
        Permission::ViewBroadcaster,
        Permission::DeleteAnyBroadcaster,
        Permission::RestoreAnyBroadcaster,
        Permission::ViewAnyBroadcasterSubmissionFilter,
        Permission::ViewAnyBroadcasterTeamMember,
        Permission::JuryVote,
        Permission::BypassMaximumAgeLimitCheck,
        Permission::BypassMinimumLengthRequirementCheck,
        Permission::BypassBannedCategoryCheck,
        Permission::CanFlagClips,
    ];

    protected array $cutterPermissions = [
        Permission::ViewAnyUser,
        Permission::ViewAnyCompilation,
        Permission::ViewCompilation,
        Permission::CreateCompilation,
        Permission::ViewAnyCategory,
        Permission::ViewCategory,
        Permission::ViewAnyComment,
        Permission::CreateComment,
        Permission::ViewAnyTag,
        Permission::ViewTag,
        Permission::ViewAnyClip,
        Permission::ViewClip,
        Permission::CreateClip,
        Permission::UpdateAnyClip,
        Permission::ViewAnyBroadcaster,
        Permission::ViewBroadcaster,
        Permission::JuryVote,
        Permission::CanSubmitClipFeedback,
        Permission::CanFlagClips,
    ];

    protected array $itPermissions = [
        Permission::ViewAnyCompilation,
        Permission::ViewCompilation,
        Permission::CreateCompilation,
        Permission::UpdateAnyCompilation,
        Permission::DeleteAnyCompilation,
        Permission::RestoreAnyCompilation,
        Permission::ViewAnyCategory,
        Permission::ViewCategory,
        Permission::CreateCategory,
        Permission::UpdateAnyCategory,
        Permission::DeleteAnyCategory,
        Permission::RestoreAnyCategory,
        Permission::ViewAnyFaqEntry,
        Permission::ViewFaqEntry,
        Permission::CreateFaqEntry,
        Permission::UpdateAnyFaqEntry,
        Permission::DeleteAnyFaqEntry,
        Permission::RestoreAnyFaqEntry,
        Permission::ViewAnyComment,
        Permission::CreateComment,
        Permission::DeleteAnyComment,
        Permission::ViewAnyTag,
        Permission::ViewTag,
        Permission::CreateTag,
        Permission::UpdateAnyTag,
        Permission::ViewAnyClip,
        Permission::ViewClip,
        Permission::CreateClip,
        Permission::UpdateAnyClip,
        Permission::DeleteAnyClip,
        Permission::RestoreAnyClip,
        Permission::ViewAnyBroadcaster,
        Permission::ViewBroadcaster,
        Permission::ViewAnyBroadcasterConsentLog,
        Permission::ViewAnyBroadcasterSubmissionFilter,
        Permission::ViewAnyBroadcasterTeamMember,
        Permission::ViewAnyAudit,
        Permission::JuryVote,
        Permission::CanFlagClips,
    ];

    protected array $juryPermissions = [
        Permission::JuryVote,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (RolePermission::count() > 0) {
            return;
        }

        $adminPermissions = array_filter(Permission::cases(), fn (Permission $value): bool => ! in_array($value, $this->adminPermissionsBlacklist));

        $permissionMapping = [
            1 => $adminPermissions, // Administrator
            2 => $this->communityManagerPermissions, // Community Manager
            3 => $this->moderatorPermissions, // Moderator
            4 => $this->cutterPermissions, // Cutter
            5 => $this->itPermissions, // IT
            6 => $this->juryPermissions, // Jury
        ];

        foreach ($permissionMapping as $roleId => $permissions) {
            $role = Role::find($roleId);

            if (empty($role)) {
                Log::warning("Role Id $roleId not found!");

                continue;
            }

            $permissions = array_map(fn (Permission $permission): array => ['permission' => $permission], $permissions);

            $role->permissions()->createMany($permissions);
        }

    }
}
