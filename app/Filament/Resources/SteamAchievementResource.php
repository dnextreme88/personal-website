<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SteamAchievementResource\Pages\CreateSteamAchievement;
use App\Filament\Resources\SteamAchievementResource\Pages\EditSteamAchievement;
use App\Filament\Resources\SteamAchievementResource\Pages\ListSteamAchievements;
use App\Models\SteamAchievement;
use BackedEnum;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SteamAchievementResource extends Resource
{
    protected static ?string $model = SteamAchievement::class;
    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-trophy';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Achievement Details')
                    ->schema([
                        TextInput::make('game_name')
                            ->maxLength(255)
                            ->minLength(2)
                            ->required(),
                        DatePicker::make('date_completed')
                            ->beforeOrEqual('today')
                            ->default(now())
                            ->helperText('Format: MM/DD/YYYY. Will be saved as YYYY-MM-DD in the DB')
                            ->required()
                            ->rules(['date', 'date_format:Y-m-d']),
                        TagsInput::make('tags')
                            ->extraAttributes(['class' => 'lowercase'])
                            ->helperText('To add the tag, press the Enter, Tab, or comma (,) keys')
                            ->nestedRecursiveRules(['max:128', 'min:2'])
                            ->nullable()
                            ->reorderable()
                            ->separator()
                            ->splitKeys([','])
                            ->suggestions(function () {
                                $tags = SteamAchievement::all()->pluck('tags')
                                    ->reduce(function (?string $carry, ?string $item): ?string {
                                        if ($item) {
                                            $carry .= $item. ',';
                                        }

                                        return $carry;
                                    });

                                $concatenated_tags = Str::of($tags)->explode(',')
                                    ->unique()
                                    ->sort()
                                    ->values();

                                return $concatenated_tags;
                            }),
                        Textarea::make('notes')
                            ->autosize()
                            ->columnSpanFull()
                            ->maxLength(255)
                            ->nullable(),
                    ])
                    ->columns(['sm' => 1, 'lg' => 2])
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('game_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_completed')
                    ->dateTime('M d, Y')
                    ->sortable(),
                TextColumn::make('tags')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
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
            'index' => ListSteamAchievements::route('/'),
            'create' => CreateSteamAchievement::route('/create'),
            'edit' => EditSteamAchievement::route('/{record}/edit'),
        ];
    }
}
