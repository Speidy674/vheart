<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Broadcaster;

use App\Enums\Broadcaster\DashboardNavigationGroup;
use App\Enums\Broadcaster\DashboardNavigationItem;
use App\Enums\Filament\LucideIcon;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class ManageCategoryFilter extends Page
{
    protected static string|null|BackedEnum $navigationIcon = LucideIcon::Folder;

    protected static ?int $navigationSort = 1000;

    protected static string|null|UnitEnum $navigationGroup = DashboardNavigationGroup::Settings;

    protected string $view = 'filament.dashboard.pages.broadcaster.manage-category-filter';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return DashboardNavigationItem::ManageCategoryFilter->getLabel();
    }

    public static function canAccess(): bool
    {
        // later we can check for permission to this specific page here
        return Filament::getTenant()?->id === auth()->user()?->id;
    }

    public function getTitle(): string|Htmlable
    {
        return Filament::getTenant()->name.' - '.DashboardNavigationItem::ManageCategoryFilter->getLabel();
    }
}
