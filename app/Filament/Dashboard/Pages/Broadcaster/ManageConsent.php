<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Broadcaster;

use App\Enums\Broadcaster\BroadcasterConsent;
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
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageConsent extends Page
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static string|null|BackedEnum $navigationIcon = LucideIcon::Settings;

    protected static ?int $navigationSort = 999;

    protected static string|null|UnitEnum $navigationGroup = DashboardNavigationGroup::Settings;

    protected static ?string $title = '';

    protected string $view = 'filament.dashboard.pages.broadcaster.manage-consent';

    public static function getNavigationLabel(): string
    {
        return DashboardNavigationItem::ManageConsent->getLabel();
    }

    public static function canAccess(): bool
    {
        // later we can check for permission to this specific page here
        return Filament::getTenant()?->id === auth()->user()?->id;
    }

    public function mount(): void
    {
        $this->form->fill(
            collect(BroadcasterConsent::cases())
                ->mapWithKeys(fn (BroadcasterConsent $case): array => [
                    "consent_{$case->value}" => $this->getRecord()->consent?->contains(fn (BroadcasterConsent $c): bool => $c === $case) ?? false,
                ])
                ->all()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(DashboardNavigationItem::ManageConsent->getLabel())
                    ->description(__('dashboard/settings/manage-consent.section.description'))
                    ->schema([Form::make(
                        collect(BroadcasterConsent::cases())
                            ->map(fn (BroadcasterConsent $case): Toggle => Toggle::make("consent_{$case->value}")
                                ->label($case->getLabel())
                                ->live()
                                ->afterStateUpdated(fn () => $this->autosave())
                            )
                            ->all()
                    ),
                    ]),
            ])
            ->statePath('data');
    }

    public function autosave(): void
    {
        $state = $this->form->getRawState();

        $consent = collect(BroadcasterConsent::cases())
            ->filter(fn (BroadcasterConsent $case) => $state["consent_{$case->value}"] ?? false)
            ->values()
            ->all();

        $this->getRecord()->update(['consent' => $consent]);
    }

    /**
     * @return Broadcaster
     */
    public function getRecord(): Model
    {
        return Filament::getTenant();
    }
}
