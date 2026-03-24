<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Broadcaster;

use App\Enums\Broadcaster\DashboardNavigationGroup;
use App\Enums\Broadcaster\DashboardNavigationItem;
use App\Enums\Filament\LucideIcon;
use App\Models\Broadcaster\Broadcaster;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSubmissions extends Page
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static string|null|BackedEnum $navigationIcon = LucideIcon::Send;

    protected static ?int $navigationSort = 999;

    protected static string|null|UnitEnum $navigationGroup = DashboardNavigationGroup::Settings;

    protected string $view = 'filament.dashboard.pages.broadcaster.manage-submissions';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return DashboardNavigationItem::ManageSubmissions->getLabel();
    }

    public static function canAccess(): bool
    {
        // later we can check for permission to this specific page here
        return Filament::getTenant()?->id === auth()->user()?->id;
    }

    public function getTitle(): string|Htmlable
    {
        return Filament::getTenant()->name.' - '.DashboardNavigationItem::ManageSubmissions->getLabel();
    }

    public function mount(): void
    {
        $this->form->fill($this->getRecord()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(DashboardNavigationItem::ManageSubmissions->getLabel())
                    ->description(__('dashboard/settings/manage-submissions.section.description'))
                    ->schema([Form::make([
                        Toggle::make('submit_user_allowed')
                            ->label('dashboard/settings/manage-submissions.form.submit_user_allowed.label')
                            ->helperText(__('dashboard/settings/manage-submissions.form.submit_user_allowed.description'))
                            ->translateLabel(),
                        Toggle::make('submit_vip_allowed')
                            ->label('dashboard/settings/manage-submissions.form.submit_vip_allowed.label')
                            ->helperText(__('dashboard/settings/manage-submissions.form.submit_vip_allowed.description'))
                            ->translateLabel(),
                        Toggle::make('submit_mods_allowed')
                            ->label('dashboard/settings/manage-submissions.form.submit_mods_allowed.label')
                            ->helperText(__('dashboard/settings/manage-submissions.form.submit_mods_allowed.description'))
                            ->translateLabel(),
                    ])
                        ->live()
                        ->afterStateUpdated(fn () => $this->autosave()),
                    ]),
            ])->statePath('data');
    }

    public function autosave(): void
    {
    }

    /**
     * @return Broadcaster
     */
    public function getRecord(): Model
    {
        return Filament::getTenant();
    }
}
