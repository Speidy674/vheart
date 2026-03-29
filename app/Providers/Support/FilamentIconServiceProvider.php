<?php

declare(strict_types=1);

namespace App\Providers\Support;

use App\Enums\Filament\LucideIcon;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\ServiceProvider;

class FilamentIconServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->configureActionsIcons();
    }

    private function configureActionsIcons(): void
    {
        FilamentIcon::register([
            ActionsIconAlias::ACTION_GROUP => LucideIcon::EllipsisVertical,
            ActionsIconAlias::CREATE_ACTION_GROUPED => LucideIcon::Plus,
            ActionsIconAlias::DELETE_ACTION => LucideIcon::Trash,
            ActionsIconAlias::DELETE_ACTION_GROUPED => LucideIcon::Trash,
            ActionsIconAlias::DELETE_ACTION_MODAL => LucideIcon::Trash,
            ActionsIconAlias::DETACH_ACTION => LucideIcon::X,
            ActionsIconAlias::DETACH_ACTION_MODAL => LucideIcon::X,
            ActionsIconAlias::DISSOCIATE_ACTION => LucideIcon::X,
            ActionsIconAlias::DISSOCIATE_ACTION_MODAL => LucideIcon::X,
            ActionsIconAlias::EDIT_ACTION => LucideIcon::SquarePen,
            ActionsIconAlias::EDIT_ACTION_GROUPED => LucideIcon::SquarePen,
            ActionsIconAlias::EXPORT_ACTION_GROUPED => LucideIcon::Download,
            ActionsIconAlias::FORCE_DELETE_ACTION => LucideIcon::Trash,
            ActionsIconAlias::FORCE_DELETE_ACTION_GROUPED => LucideIcon::Trash,
            ActionsIconAlias::FORCE_DELETE_ACTION_MODAL => LucideIcon::Trash,
            ActionsIconAlias::IMPORT_ACTION_GROUPED => LucideIcon::Upload,
            ActionsIconAlias::MODAL_CONFIRMATION => LucideIcon::TriangleAlert,
            ActionsIconAlias::REPLICATE_ACTION => LucideIcon::Copy,
            ActionsIconAlias::REPLICATE_ACTION_GROUPED => LucideIcon::Copy,
            ActionsIconAlias::RESTORE_ACTION => LucideIcon::Undo2,
            ActionsIconAlias::RESTORE_ACTION_GROUPED => LucideIcon::Undo2,
            ActionsIconAlias::RESTORE_ACTION_MODAL => LucideIcon::Undo2,
            ActionsIconAlias::VIEW_ACTION => LucideIcon::Eye,
            ActionsIconAlias::VIEW_ACTION_GROUPED => LucideIcon::Eye,
        ]);
    }
}
