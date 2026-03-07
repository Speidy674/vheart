<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\FeatureFlag;
use App\Enums\NavigationGroup;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class FeatureFlags extends Page
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static string|null|BackedEnum $navigationIcon = Heroicon::Cog8Tooth;

    protected static string|null|UnitEnum $navigationGroup = NavigationGroup::Administration;

    protected string $view = 'filament.pages.feature-flags';

    public static function canAccess(): bool
    {
        return auth()->user()?->getRole()?->id === 0;
    }

    public function mount(): void
    {
        $existingFlags = DB::table('feature_flags')->pluck('enabled', 'name')->toArray();
        $formData = [];

        foreach (FeatureFlag::cases() as $case) {
            $configValue = config($case->configIdentifier());
            $isOverridden = ! is_null($configValue);

            $formData[$case->value] = $isOverridden
                ? (bool) $configValue
                : (bool) ($existingFlags[$case->value] ?? $case->getDefaultState());
        }

        $this->getSchema('form')->fill($formData);
    }

    public function form(Schema $schema): Schema
    {
        $form = [];

        foreach (FeatureFlag::cases() as $case) {
            $helperText = $case->value;

            if ($case->getDescription()) {
                $helperText .= " - {$case->getDescription()}";
            }

            $defaultStateText = $case->getDefaultState() ? 'Enabled' : 'Disabled';
            $helperText .= " (Default: {$defaultStateText})";

            $configValue = config($case->configIdentifier());
            $isOverridden = ! is_null($configValue);

            $actions = [];

            if ($case->getIssue()) {
                $actions[] = Action::make('issue')
                    ->icon(Heroicon::Link)
                    ->tooltip('View Issue')
                    ->url($case->getIssue(), shouldOpenInNewTab: true);
            }

            if ($isOverridden) {
                $overrideState = $configValue ? 'Enabled' : 'Disabled';
                $actions[] = Action::make('locked')
                    ->color('danger')
                    ->icon(Heroicon::LockClosed)
                    ->tooltip("Forced as {$overrideState} by environment")
                    ->disabled();
            }

            $form[] = Toggle::make($case->value)
                ->label($case->getLabel() ?? $case->name)
                ->default($case->getDefaultState())
                ->disabled($isOverridden)
                ->dehydrated(! $isOverridden)
                ->hintActions($actions)
                ->helperText($helperText);
        }

        return $schema
            ->schema([
                Section::make('Feature Flags')
                    ->compact()
                    ->schema($form)
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->requiresConfirmation()
                ->label('Save')
                ->action(function (): void {
                    $state = $this->getSchema('form')->getState();
                    $upsertData = [];
                    $deleteNames = [];

                    foreach ($state as $name => $enabled) {
                        $flag = FeatureFlag::tryFrom($name);

                        if ($flag && $enabled === $flag->getDefaultState()) {
                            $deleteNames[] = $name;
                        } else {
                            $upsertData[] = [
                                'name' => $name,
                                'enabled' => $enabled,
                            ];
                        }
                    }

                    try {
                        DB::transaction(static function () use ($upsertData, $deleteNames, $state): void {
                            if ($upsertData !== []) {
                                DB::table('feature_flags')->upsert(
                                    $upsertData,
                                    ['name'],
                                    ['enabled']
                                );
                            }

                            if ($deleteNames !== []) {
                                DB::table('feature_flags')->whereIn('name', $deleteNames)->delete();
                            }

                            foreach ($state as $name => $enabled) {
                                Cache::forever(FeatureFlag::tryFrom($name)?->cacheIdentifier(), $enabled);
                            }
                        });

                        Notification::make()
                            ->success()
                            ->title('Saved')
                            ->send();
                    } catch (Exception $e) {
                        report($e);

                        Notification::make()
                            ->danger()
                            ->title('Failed to save')
                            ->body('There was an error updating the feature flags.')
                            ->send();
                    }
                }),
        ];
    }
}
