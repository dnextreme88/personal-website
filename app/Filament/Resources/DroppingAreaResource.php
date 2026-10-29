<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DroppingAreaResource\Pages\CreateDroppingArea;
use App\Filament\Resources\DroppingAreaResource\Pages\EditDroppingArea;
use App\Filament\Resources\DroppingAreaResource\Pages\ListDroppingAreas;
use App\Models\DroppingArea;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DroppingAreaResource extends Resource
{
    protected static ?string $model = DroppingArea::class;
    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-map-pin';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dropping Area Details')
                    ->schema([
                        TextInput::make('address')
                            ->helperText('The building, eg. Mabini Shopping Center. A change of address shows a transfer marker on the route')
                            ->maxLength(255)
                            ->minLength(2)
                            ->nullable(),
                        TextInput::make('unit')
                            ->helperText('eg. Room 203 or Room 211 Stall 6')
                            ->maxLength(128)
                            ->minLength(2)
                            ->required(),
                        TextInput::make('opens_at')
                            ->helperText('eg. Mondays - Saturdays 9:00 AM; Sundays 10:00 AM')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('closes_at')
                            ->helperText('eg. Mondays - Saturdays 5:00 PM; Sundays 3:00 PM')
                            ->maxLength(255)
                            ->nullable(),
                        DatePicker::make('started_on')
                            ->beforeOrEqual('today')
                            ->helperText('The date this dropping area started')
                            ->nullable(),
                        Toggle::make('is_current')
                            ->helperText('Turn on while this dropping area is still active')
                            ->label('Current')
                            ->default(false),
                        Textarea::make('note')
                            ->autosize()
                            ->columnSpanFull()
                            ->maxLength(255)
                            ->nullable(),
                        /* TODO: TO FIX LOGIC
                        FileUpload::make('image_path')
                            ->columnSpanFull()
                            ->directory('dropping-areas')
                            ->disk('public')
                            ->image()
                            ->label('Image')
                            ->required(),
                        */
                    ])
                    ->columns(['sm' => 1, 'lg' => 2])
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /* TODO: TO FIX LOGIC
                ImageColumn::make('image_path')
                    ->disk('public')
                    ->label('Image'),
                */
                TextColumn::make('address')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('opens_at')
                    ->placeholder('—')
                    ->wrap(),
                TextColumn::make('closes_at')
                    ->placeholder('—')
                    ->wrap(),
                TextColumn::make('started_on')
                    ->date('M d, Y')
                    ->placeholder('—')
                    ->sortable(),
                IconColumn::make('is_current')
                    ->boolean()
                    ->label('Current')
                    ->sortable(),
                TextColumn::make('note')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('started_on', 'desc')
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
            'index' => ListDroppingAreas::route('/'),
            'create' => CreateDroppingArea::route('/create'),
            'edit' => EditDroppingArea::route('/{record}/edit'),
        ];
    }
}
