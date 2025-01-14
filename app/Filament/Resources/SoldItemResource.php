<?php

namespace App\Filament\Resources;

use App\Enums\Conditions;
use App\Enums\DroppingAreas;
use App\Enums\PaymentMethods;
use App\Enums\Remittances;
use App\Enums\SellMethods;
use App\Enums\ShipmentLocations;
use App\Enums\Sizes;
use App\Filament\Resources\SoldItemResource\Pages;
use App\Models\PayMethod;
use App\Models\SellMethod;
use App\Models\SoldItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SoldItemResource extends Resource
{
    protected static ?string $model = SoldItem::class;
    protected static ?string $activeNavigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Item Details')
                    ->schema([
                        TextInput::make('brand')
                            ->datalist(fn (): Collection =>
                                SoldItem::all()->pluck('brand')
                                    ->unique()
                                    ->sort()
                                    ->values()
                            )
                            ->maxLength(128)
                            ->minLength(2)
                            ->required(),
                        TextInput::make('name')
                            ->maxLength(128)
                            ->minLength(2),
                        TextInput::make('type')
                            ->maxLength(64)
                            ->minLength(2)
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->rules(['gt:0']),
                        Select::make('condition')
                            ->options(Conditions::class)
                            ->required(),
                        TextInput::make('size')
                            ->datalist(array_column(Sizes::cases(), 'value'))
                            ->maxLength(32)
                            ->required(),
                        DatePicker::make('date_sold')
                            ->beforeOrEqual('today')
                            ->helperText('Format: MM/DD/YYYY. Will be saved as YYYY-MM-DD in the DB')
                            ->required()
                            ->rules(['date', 'date_format:Y-m-d']),
                        TagsInput::make('tags')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'lowercase'])
                            ->helperText('To add the tag, press the Enter, Tab, or comma (,) keys')
                            ->nestedRecursiveRules(['max:128', 'min:2'])
                            ->nullable()
                            ->reorderable()
                            ->separator()
                            ->splitKeys([','])
                            ->suggestions(function () {
                                $tags = SoldItem::all()->pluck('tags')
                                    ->reduce(function (?string $carry, string $item): string {
                                        $carry .= $item. ',';

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
                    ->columns(['sm' => 1, 'md' => 2]),
                Section::make('Payment Method')
                    ->schema([
                        Select::make('pay_method_name')
                            ->label('Pay method')
                            ->live()
                            ->options(PaymentMethods::class)
                            ->required(),
                        TextInput::make('pay_method_location')
                            ->datalist(function () {
                                $locations = PayMethod::where('method', PaymentMethods::CASH_ON_HAND)->pluck('remittance_location')
                                    ->reduce(function (?string $carry, string $item): string {
                                        $carry .= $item. ',';

                                        return $carry;
                                    });

                                $concatenated_locations = Str::of($locations)->explode(',')
                                    ->unique()
                                    ->sort()
                                    ->values();

                                return $concatenated_locations;
                            })
                            ->label('Location')
                            ->maxLength(128)
                            ->minLength(2)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('pay_method_name') == PaymentMethods::CASH_ON_HAND->value),
                        Select::make('pay_method_location')
                            ->label('Dropping Area')
                            ->options(DroppingAreas::class)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('pay_method_name') == PaymentMethods::DROPPING_AREA_CASHOUT->value),
                        Select::make('pay_method_location')
                            ->options(Remittances::class)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('pay_method_name') == PaymentMethods::REMITTANCE->value),
                    ])
                    ->columns(['sm' => 1, 'lg' => 2]),
                Section::make('Sell Method')
                    ->schema([
                        Select::make('sell_method_name')
                            ->live()
                            ->options(SellMethods::class)
                            ->required(),
                        TextInput::make('sell_method_location')
                            ->datalist(function () {
                                $locations = SellMethod::where('method', SellMethods::MEETUP)->pluck('location')
                                    ->reduce(function (?string $carry, string $item): string {
                                        $carry .= $item. ',';

                                        return $carry;
                                    });

                                $concatenated_locations = Str::of($locations)->explode(',')
                                    ->unique()
                                    ->sort()
                                    ->values();

                                return $concatenated_locations;
                            })
                            ->maxLength(128)
                            ->minLength(2)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('sell_method_name') == SellMethods::MEETUP->value),
                        Select::make('sell_method_location')
                            ->label('Dropping Area')
                            ->options(DroppingAreas::class)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('sell_method_name') == SellMethods::DROPPING->value),
                        Select::make('sell_method_location')
                            ->label('Shipping Company')
                            ->options(ShipmentLocations::class)
                            ->required()
                            ->visible(fn (Get $get): bool => $get('sell_method_name') == SellMethods::SHIPMENT->value),

                    ])
                    ->columns(['sm' => 1, 'lg' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->words(5),
                TextColumn::make('price')
                    ->sortable(),
                TextColumn::make('condition'),
                TextColumn::make('size'),
                TextColumn::make('date_sold')
                    ->dateTime('M d, Y')
                    ->sortable(),
                TextColumn::make('tags')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pay_method.method'),
                TextColumn::make('pay_method.remittance_location')
                    ->label('Remittance location')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sell_method.method'),
                TextColumn::make('sell_method.location')
                    ->label('Sell location')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('condition')
                    ->options(Conditions::class),
                SelectFilter::make('pay_method')
                    ->options(PaymentMethods::class)
                    ->query(function (Builder $query, array $data) {
                        // REF: https://v2.filamentphp.com/tricks/use-selectfilter-on-distant-relationships
                        if (!empty($data['value'])) {
                            $query->whereHas('pay_method', fn (Builder $query) => $query->getMethod($data['value']));
                        }
                    }),
                SelectFilter::make('sell_method')
                    ->options(SellMethods::class)
                    ->query(function (Builder $query, array $data) {
                        // REF: https://v2.filamentphp.com/tricks/use-selectfilter-on-distant-relationships
                        if (!empty($data['value'])) {
                            $query->whereHas('sell_method', fn (Builder $query) => $query->getMethod($data['value']));
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListSoldItems::route('/'),
            'create' => Pages\CreateSoldItem::route('/create'),
            'edit' => Pages\EditSoldItem::route('/{record}/edit'),
        ];
    }
}
