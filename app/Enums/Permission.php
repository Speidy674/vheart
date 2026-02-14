<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\HasHeadlineLabel;
use Filament\Support\Contracts\HasLabel;

enum Permission: string implements HasLabel
{
    use HasHeadlineLabel;

    // User
    case ViewAnyUser = 'view_any_user';
    case ViewUser = 'view_user';
    case CreateUser = 'create_user';
    case UpdateAnyUser = 'update_any_user';
    case DeleteAnyUser = 'delete_any_user';
    case RestoreAnyUser = 'restore_any_user';
    case ForceDeleteAnyUser = 'force_delete_any_user';

    // Report
    case ViewAnyReport = 'view_any_report';
    case ViewReport = 'view_report';
    case CreateReport = 'create_report';
    case UpdateAnyReport = 'update_any_report';
    case DeleteAnyReport = 'delete_any_report';
    case RestoreAnyReport = 'restore_any_report';
    case ForceDeleteAnyReport = 'force_delete_any_report';

    // Compilation
    case ViewAnyCompilation = 'view_any_compilation';
    case ViewCompilation = 'view_compilation';
    case CreateCompilation = 'create_compilation';
    case UpdateAnyCompilation = 'update_any_compilation';
    case DeleteAnyCompilation = 'delete_any_compilation';
    case RestoreAnyCompilation = 'restore_any_compilation';
    case ForceDeleteAnyCompilation = 'force_delete_any_compilation';

    // Category
    case ViewAnyCategory = 'view_any_category';
    case ViewCategory = 'view_category';
    case CreateCategory = 'create_category';
    case UpdateAnyCategory = 'update_any_category';
    case DeleteAnyCategory = 'delete_any_category';
    case RestoreAnyCategory = 'restore_any_category';
    case ForceDeleteAnyCategory = 'force_delete_any_category';

    // FAQ
    case ViewAnyFaq = 'view_any_faq';
    case ViewFaq = 'view_faq';
    case CreateFaq = 'create_faq';
    case UpdateAnyFaq = 'update_any_faq';
    case DeleteAnyFaq = 'delete_any_faq';
    case RestoreAnyFaq = 'restore_any_faq';
    case ForceDeleteAnyFaq = 'force_delete_any_faq';

    // Comments
    case ViewAnyComment = 'view_any_comment';
    case CreateComment = 'create_comment';
    case DeleteAnyComment = 'delete_any_comment';

    // Tag
    case ViewAnyTag = 'view_any_tag';
    case ViewTag = 'view_tag';
    case CreateTag = 'create_tag';
    case UpdateAnyTag = 'update_any_tag';
    case DeleteAnyTag = 'delete_any_tag';

    // Non-Model stuff
    case JuryVote = 'jury_vote';
}
