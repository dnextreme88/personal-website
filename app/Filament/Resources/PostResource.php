<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-chat-bubble-oval-left';
    protected static string|UnitEnum|null $navigationGroup = 'Blog';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                DatePicker::make('date_published')
                    ->default(Carbon::now())
                    ->helperText('The post will show on this date when published.')
                    ->native(false)
                    ->required(),
                Toggle::make('is_published')
                    ->default(true)
                    ->helperText('Toggle whether to publish the post or in draft. If the post is in draft, the post cannot be viewed on the site.')
                    ->label('Published'),
                TextInput::make('title')
                    ->maxLength(64)
                    ->minLength(3)
                    ->required()
                    ->unique(ignoreRecord: true),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles', 'table'])
                    ->extraInputAttributes(['class' => 'min-h-[360px]'])
                    ->required(),
                Select::make('related_post_ids')
                    ->columnSpanFull()
                    ->helperText('Related posts will show up at the end of this blog post')
                    ->label('Related Posts')
                    ->multiple()
                    ->options(fn (?Post $record): array => Post::query()
                        ->when($record, fn (Builder $query) => $query->whereKeyNot($record->id))
                        ->orderBy('title')
                        ->pluck('title', 'id')
                        ->all())
                    ->placeholder('Select a related post')
                    ->searchable(),
                Section::make('Post Details')
                    ->columnSpanFull()
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
                    ->badge()
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
                    ->getStateUsing(fn ($record): string => strip_tags($record->description))
                    ->searchable()
                    ->words(8),
                TextColumn::make('date_published')
                    ->dateTime('M d, Y')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                TextColumn::make('updated_at')
                    ->dateTime('F j, Y H:i:s A')
                    ->sortable(),
            ])
            ->defaultSort('date_published', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(fn (): Collection => Category::all()->pluck('name', 'id')
                        ->sort()
                    )
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('category_id', $data['value']);
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
