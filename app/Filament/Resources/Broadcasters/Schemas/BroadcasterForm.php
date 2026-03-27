<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters\Schemas;

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Enums\Broadcaster\BroadcasterPermission;
use App\Enums\Filament\LucideIcon;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class BroadcasterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            self::makeConsentSection(),
            self::makeSubmitPermissionsSection(),
        ]);
    }

    private static function makeConsentSection(): Section
    {
        return Section::make('Consent')
            ->schema([
                CheckboxList::make('consent')
                    ->options(BroadcasterConsent::class)
                    ->gridDirection('row')
                    ->label('Given Consents')
                    ->columns(2),

                CheckboxList::make('twitch_mod_permissions')
                    ->options(BroadcasterPermission::class)
                    ->gridDirection('row')
                    ->label('Mod Permissions')
                    ->columns(2)
                    // hidden for now since we did not implement that currently
                    ->hidden(),
            ]);
    }

    private static function makeSubmitPermissionsSection(): Section
    {
        return Section::make('Submission Permissions')
            ->schema([
                Toggle::make('submit_user_allowed')
                    ->afterStateUpdated(function (bool $state, Set $set): void {
                        if ($state) {
                            $set('submit_vip_allowed', true);
                            $set('submit_mods_allowed', true);
                        }
                    })
                    ->onIcon(LucideIcon::Check)
                    ->offIcon(LucideIcon::X)
                    ->onColor('success')
                    ->label('Everyone')
                    ->live(),

                Toggle::make('submit_vip_allowed')
                    ->afterStateUpdated(function (bool $state, Set $set): void {
                        if (! $state) {
                            $set('submit_user_allowed', false);
                        }
                    })
                    ->onIcon(LucideIcon::Check)
                    ->offIcon(LucideIcon::X)
                    ->onColor('success')
                    ->label('VIPs')
                    ->live(),

                Toggle::make('submit_mods_allowed')
                    ->afterStateUpdated(function (bool $state, Set $set): void {
                        if (! $state) {
                            $set('submit_user_allowed', false);
                        }
                    })
                    ->onIcon(LucideIcon::Check)
                    ->offIcon(LucideIcon::X)
                    ->onColor('success')
                    ->label('Mods')
                    ->live(),
            ]);
    }
}
