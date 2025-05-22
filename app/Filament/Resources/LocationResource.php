<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\City;
use App\Models\District;
use App\Models\Location;
use App\Models\PostalCode;
use App\Models\Province;
use App\Models\Subdistrict;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Locations';

    protected static ?string $pluralLabel = 'Locations';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('location_name')
                    ->label('Location Name')
                    ->required()
                    ->maxLength(45),
                Textarea::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Select::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'province_name')
                    ->searchable()
                    ->lazy()
                    ->options(fn () => Cache::remember('provinces', 60 * 60 * 24, fn () => Province::pluck('province_name', 'id')->take(100)->toArray()))
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('city_id', null);
                        $set('district_id', null);
                        $set('subdistrict_id', null);
                        $set('poscode_id', null);
                    })
                    ->required()
                    ->preload(),
                Select::make('city_id')
                    ->label('City')
                    ->relationship('city', 'city_name')
                    ->searchable()
                    ->lazy()
                    ->options(function (callable $get) {
                        $provinceId = $get('province_id');
                        if (!$provinceId) return [];
                        return Cache::remember("cities_province_{$provinceId}", 60 * 60 * 24, fn () => City::where('province_id', $provinceId)->pluck('city_name', 'id')->toArray());
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('district_id', null);
                        $set('subdistrict_id', null);
                        $set('poscode_id', null);
                    })
                    ->required()
                    ->preload(),
                Select::make('district_id')
                    ->label('District')
                    ->relationship('district', 'district_name')
                    ->searchable()
                    ->lazy()
                    ->options(function (callable $get) {
                        $cityId = $get('city_id');
                        if (!$cityId) return [];
                        return Cache::remember("districts_city_{$cityId}", 60 * 60 * 24, fn () => District::where('city_id', $cityId)->pluck('district_name', 'id')->toArray());
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('subdistrict_id', null);
                        $set('poscode_id', null);
                    })
                    ->required()
                    ->preload(),
                Select::make('subdistrict_id')
                    ->label('Subdistrict')
                    ->relationship('subdistrict', 'subdistrict_name')
                    ->searchable()
                    ->lazy()
                    ->options(function (callable $get) {
                        $districtId = $get('district_id');
                        if (!$districtId) return [];
                        return Cache::remember("subdistricts_district_{$districtId}", 60 * 60 * 24, fn () => Subdistrict::where('district_id', $districtId)->pluck('subdistrict_name', 'id')->toArray());
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('poscode_id', null);
                    })
                    ->required()
                    ->preload(),
                Select::make('poscode_id')
                    ->label('Postal Code')
                    ->relationship('postalCode', 'poscode')
                    ->searchable()
                    ->lazy()
                    ->options(function (callable $get) {
                        $subdistrictId = $get('subdistrict_id');
                        if (!$subdistrictId) return [];
                        return Cache::remember("postalcodes_subdistrict_{$subdistrictId}", 60 * 60 * 24, fn () => PostalCode::where('subdistrict_id', $subdistrictId)->pluck('poscode', 'id')->toArray());
                    })
                    ->required()
                    ->preload(),
                Toggle::make('status')
                    ->label('Status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Location::query()->with([
                    'province' => fn ($query) => $query->select('id', 'province_name'),
                    'city' => fn ($query) => $query->select('id', 'city_name'),
                    'district' => fn ($query) => $query->select('id', 'district_name'),
                    'subdistrict' => fn ($query) => $query->select('id', 'subdistrict_name'),
                    'postalCode' => fn ($query) => $query->select('id', 'poscode'),
                    'creator' => fn ($query) => $query->select('id', 'name'),
                    'updater' => fn ($query) => $query->select('id', 'name'),
                ])
            )
            ->columns([
                TextColumn::make('location_name')
                    ->label('Location Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('Address')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('province.province_name')
                    ->label('Province')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city.city_name')
                    ->label('City')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('district.district_name')
                    ->label('District')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subdistrict.subdistrict_name')
                    ->label('Subdistrict')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('postalCode.poscode')
                    ->label('Postal Code')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('status')
                    ->label('Active')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->label('Updated By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
                SelectFilter::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'province_name')
                    ->searchable()
                    ->options(fn () => Cache::remember('province_filter', 60 * 60 * 24, fn () => Province::pluck('province_name', 'id')->take(100)->toArray())),
                SelectFilter::make('city_id')
                    ->label('City')
                    ->relationship('city', 'city_name')
                    ->searchable()
                    ->options(fn () => Cache::remember('city_filter', 60 * 60 * 24, fn () => City::pluck('city_name', 'id')->take(100)->toArray())),
                SelectFilter::make('district_id')
                    ->label('District')
                    ->relationship('district', 'district_name')
                    ->searchable()
                    ->options(fn () => Cache::remember('district_filter', 60 * 60 * 24, fn () => District::pluck('district_name', 'id')->take(100)->toArray())),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
