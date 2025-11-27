<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Blog\Category;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $activeNavigationIcon = 'heroicon-s-rectangle-stack';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (Category $category): bool => $category->postsCount == 0),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
