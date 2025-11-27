<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Models\Blog\Category;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static BackedEnum|string|null $activeNavigationIcon = 'heroicon-s-rectangle-stack';
    protected static UnitEnum|string|null $navigationGroup = 'Blog';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(64)
                    ->minLength(3)
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('description')
                    ->columnSpanFull(),
                Section::make('Category Details')
                    ->columns(2)
                    ->hidden(fn (string $operation): bool => $operation === 'create')
                    ->schema([
                        Placeholder::make('created_at')
                            ->content(fn (Category $category): string => $category->created_at->format('F j, Y H:i:s A')),
                        Placeholder::make('updated_at')
                            ->content(fn (Category $category): string => $category->updated_at->format('F j, Y H:i:s A')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->words(5),
                TextColumn::make('created_at')
                    ->dateTime('F j, Y H:i:s A')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('F j, Y H:i:s A')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (Category $category): bool => $category->postsCount == 0),
            ])
            ->toolbarActions([]);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
