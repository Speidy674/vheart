<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label(__('filament-panels::auth/pages/edit-profile.form.email.label'))
                    ->email()
                    ->nullable()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->live(debounce: 500),
            ]);
    }
}
