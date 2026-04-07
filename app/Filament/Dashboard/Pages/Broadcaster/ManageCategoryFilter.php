<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Broadcaster;

use App\Actions\ImportCategoryAction;
use App\Enums\Broadcaster\DashboardNavigationGroup;
use App\Enums\Broadcaster\DashboardNavigationItem;
use App\Enums\Filament\LucideIcon;
use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\BroadcasterSubmissionFilter;
use App\Models\Category;
use App\Services\Twitch\Data\CategoryDto;
use App\Services\Twitch\Data\GameDto;
use App\Services\Twitch\Enums\TwitchEndpoints;
use App\Services\Twitch\TwitchService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use UnitEnum;

class ManageCategoryFilter extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

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

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getBaseQuery())
            ->columns([
                ImageColumn::make('box_art')
                    ->state(fn (BroadcasterSubmissionFilter $record) => $record->filterable?->proxiedContentUrl())
                    ->label('')
                    ->imageHeight(100)
                    ->grow(false)
                    ->width(75),
                TextColumn::make('filterable.title')
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHasMorph(
                        'filterable',
                        Category::class,
                        fn (Builder $q) => $q->where('title', 'ilike', "%{$search}%"),
                    ))
                    ->label('dashboard/settings/manage-category-filters.table.title')
                    ->translateLabel(),
                ToggleColumn::make('state')
                    ->label('dashboard/settings/manage-category-filters.table.state')
                    ->translateLabel()
                    ->onIcon(LucideIcon::Check)
                    ->offIcon(LucideIcon::X)
                    ->onColor('success')
                    ->offColor('danger')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('state')
                    ->label('dashboard/settings/manage-category-filters.filters.state.label')
                    ->translateLabel()
                    ->placeholder(__('dashboard/settings/manage-category-filters.filters.state.placeholder'))
                    ->trueLabel(__('dashboard/settings/manage-category-filters.filters.state.true'))
                    ->falseLabel(__('dashboard/settings/manage-category-filters.filters.state.false'))
                    ->queries(
                        true: fn (Builder $q) => $q->whereState(true),
                        false: fn (Builder $q) => $q->whereState(false),
                    ),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->heading(DashboardNavigationItem::ManageCategoryFilter->getLabel())
            ->description(__('dashboard/settings/manage-category-filters.section.description'))
            ->toolbarActions([
                $this->makeCreateAction(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return Broadcaster
     */
    public function getOwnerRecord(): Model
    {
        return Filament::getTenant();
    }

    private function makeCreateAction(): CreateAction
    {
        return CreateAction::make()
            ->schema([
                Select::make('filterable_id')
                    ->getSearchResultsUsing(
                        function (string $search, TwitchService $twitchService) {
                            $search = mb_trim($search);
                            $categorys = collect($twitchService->asSessionUser()->searchCategories($search, 100))
                                ->each(fn (CategoryDto $category) => Cache::put("twitch:category:$category->id", $category, now()->addMinutes(30)))
                                ->map(fn (CategoryDto $item): array => ['title' => $item->name, 'id' => $item->id]);

                            $category = Category::where('title', 'ilike', "%$search%")
                                ->whereNotExists(function ($query): void {
                                    $query->from('broadcaster_submission_filters')
                                        ->whereColumn('broadcaster_submission_filters.filterable_id', (new Category)->getTable().'.id')
                                        ->where('broadcaster_submission_filters.filterable_type', $this->getMorphClass())
                                        ->where('broadcaster_submission_filters.broadcaster_id', $this->getOwnerRecord()->id);
                                })
                                ->limit(5)
                                ->pluck('title', 'id')
                                ->map(fn (string $title, int $id): array => ['id' => $id, 'title' => $title])
                                ->merge($categorys)
                                ->unique('id')
                                ->take(100);

                            $existingIds = $this->getBaseQuery()
                                ->whereIn('filterable_id', $category->pluck('id'))->pluck('filterable_id')
                                ->map(fn ($id): string => (string) $id)
                                ->all();

                            return $category->reject(fn (array $item): bool => in_array((string) $item['id'], $existingIds, true))
                                ->values()
                                ->sortBy(fn (array $item): int => levenshtein(mb_strtolower($search), mb_strtolower((string) $item['title'])))
                                ->mapWithKeys(fn (array $item): array => [$item['id'] => $item['title']])->each(fn ($title, $id) => Log::info('Testing', ['id' => $id, 'title' => $title]));
                        })
                    ->getOptionLabelUsing(function (string $value, TwitchService $twitchService, ImportCategoryAction $importCategoryAction) {
                        if ($title = Category::find((int) $value)?->title) {
                            return $title;
                        }

                        if ($category = Cache::get("twitch:category:$value")) {
                            $category = $importCategoryAction->execute($category);

                            return $category->title;
                        }

                        $categories = $twitchService->collection(TwitchEndpoints::GetGames, [
                            'id' => $value,
                        ]);

                        /** @var GameDto $game */
                        $game = array_first($categories);

                        $category = $importCategoryAction->execute($game);

                        return $category->title;
                    })
                    ->label('dashboard/settings/manage-category-filters.table.title')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->searchable()
                    ->required(),
                Toggle::make('state')
                    ->label('dashboard/settings/manage-category-filters.table.state')
                    ->translateLabel()
                    ->onIcon(LucideIcon::Check)
                    ->offIcon(LucideIcon::X)
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->mutateDataUsing(function (array $data): array {
                $data['broadcaster_id'] = $this->getOwnerRecord()->id;
                $data['filterable_type'] = $this->getMorphClass();

                return $data;
            });
    }

    private function getMorphClass(): string
    {
        return (new Category)->getMorphClass();
    }

    private function getBaseQuery(): Builder
    {
        $tenant = $this->getOwnerRecord();

        return $tenant->filters()->getQuery()->where('filterable_type', $this->getMorphClass());
    }
}
