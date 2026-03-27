<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters;

use App\Enums\Filament\LucideIcon;
use App\Filament\Resources\Broadcasters\Pages\CreateBroadcaster;
use App\Filament\Resources\Broadcasters\Pages\EditBroadcaster;
use App\Filament\Resources\Broadcasters\Pages\ListBroadcasters;
use App\Filament\Resources\Broadcasters\Pages\ViewBroadcaster;
use App\Filament\Resources\Broadcasters\Schemas\BroadcasterForm;
use App\Filament\Resources\Broadcasters\Schemas\BroadcasterInfolist;
use App\Filament\Resources\Broadcasters\Tables\BroadcastersTable;
use App\Models\Broadcaster\Broadcaster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BroadcasterResource extends Resource
{
    protected static ?string $model = Broadcaster::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::UserStar;

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record->user->name;
    }

    public static function form(Schema $schema): Schema
    {
        return BroadcasterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BroadcasterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BroadcastersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBroadcasters::route('/'),
            'create' => CreateBroadcaster::route('/create'),
            'view' => ViewBroadcaster::route('/{record}'),
            'edit' => EditBroadcaster::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
