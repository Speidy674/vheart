<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Broadcaster;

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Enums\Broadcaster\DashboardNavigationGroup;
use App\Enums\Broadcaster\DashboardNavigationItem;
use App\Enums\Clips\ClipStatus;
use App\Enums\Filament\LucideIcon;
use App\Models\Broadcaster\Broadcaster;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * @property-read Schema $consentForm
 * @property-read Schema $defaultClipStatusForm
 */
class ManageConsent extends Page implements HasForms
{
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $consentFormData = [];

    public ?array $defaultClipStatusFormData = [];

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
        $this->consentForm->fill(
            collect(BroadcasterConsent::cases())
                ->mapWithKeys(fn (BroadcasterConsent $case): array => [
                    "consent_{$case->value}" => $this->getRecord()->consent?->contains(fn (BroadcasterConsent $c): bool => $c === $case) ?? false,
                ])
                ->all()
        );
        $this->defaultClipStatusForm->fill($this->getRecord()->only(['default_clip_status']));
    }

    public function defaultClipStatusForm(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('default_clip_status')
                ->heading(__('dashboard/settings/manage-consent.sections.default_clip_status.label'))
                ->description(__('dashboard/settings/manage-consent.sections.default_clip_status.description'))
                ->schema([Form::make([
                    Radio::make('default_clip_status')
                        ->options(
                            collect(ClipStatus::defaultableOptions())
                                ->mapWithKeys(fn (ClipStatus $status): array => [$status->value => $status->getLabel()])
                                ->toArray()
                        )
                        ->descriptions(
                            collect(ClipStatus::defaultableOptions())
                                ->mapWithKeys(fn (ClipStatus $status): array => [$status->value => __('onboarding.setup.default_clip_status.options.'.Str::snake($status->name))])
                                ->toArray()
                        ),
                ])
                    ->live()
                    ->afterStateUpdated(fn () => $this->defaultClipStatusFormAutosave()),
                ]),
        ])->statePath('defaultClipStatusFormData');
    }

    public function defaultClipStatusFormAutosave(): void
    {
        $state = $this->defaultClipStatusForm->getState();
        $this->getRecord()->update($state);
        $this->getRecord()->refresh();
        $this->mount();
    }

    public function consentForm(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(DashboardNavigationItem::ManageConsent->getLabel())
                ->description(__('dashboard/settings/manage-consent.sections.consent.description'))
                ->schema([Form::make(
                    collect(BroadcasterConsent::cases())
                        ->map(fn (BroadcasterConsent $case): Toggle => Toggle::make("consent_{$case->value}")
                            ->label($case->getLabel())
                            ->live()
                            ->afterStateUpdated(fn () => $this->consentFormAutosave())
                        )
                        ->all()
                ),
                ]),
        ])
            ->statePath('consentFormData');
    }

    public function consentFormAutosave(): void
    {
        $state = $this->consentForm->getRawState();

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

    protected function getForms(): array
    {
        return [
            'consentForm',
            'defaultClipStatusForm',
        ];
    }
}
