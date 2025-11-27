<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $activeNavigationIcon = 'heroicon-s-chat-bubble-oval-left';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                DatePicker::make('date_published')
                    ->default(Carbon::now())
                    ->native(false)
                    ->required(),
                TextInput::make('title')
                    ->maxLength(64)
                    ->minLength(3)
                    ->required()
                    ->unique(ignoreRecord: true),
                MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles', 'table'])
                    ->required(),
                Section::make('Post Details')
                    ->columns(2)
                    ->hidden(fn (string $operation): bool => $operation === 'create')
                    ->schema([
                        Placeholder::make('created_at')
                            ->content(fn (Post $post): string => $post->created_at->format('F j, Y H:i:s A')),
                        Placeholder::make('updated_at')
                            ->content(fn (Post $post): string => $post->updated_at->format('F j, Y H:i:s A')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->words(5),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description')
                    ->searchable()
                    ->words(5),
                TextColumn::make('date_published')
                    ->dateTime('M d, Y'),
                TextColumn::make('updated_at')
                    ->dateTime('F j, Y H:i:s A')
                    ->sortable(),
            ])
            ->defaultSort('date_published', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(fn (): Collection =>
                        Category::all()->pluck('name', 'id')
                            ->sort()
                    )
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('category_id', $data['value']);
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
