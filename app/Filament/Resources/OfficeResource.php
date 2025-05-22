<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeResource\Pages;
use App\Filament\Resources\OfficeResource\RelationManagers;
use App\Models\Office;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Offices';

    protected static ?string $pluralLabel = 'Offices';

    protected static ?string $navigationGroup = 'Master Data';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('code_office')
                ->label('Office Code')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TextInput::make('office_name')
                ->label('Office Name')
                ->required()
                ->maxLength(255),
            Textarea::make('address')
                ->label('Address')
                ->maxLength(65535)
                ->columnSpan(2),
            TextInput::make('pic_name')
                ->label('PIC Name')
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255),
            TextInput::make('phone')
                ->label('Phone')
                ->tel()
                ->maxLength(255),
            Select::make('vendor_id')
                ->label('Vendor')
                ->relationship('vendor', 'vendor_name')
                ->searchable()
                ->preload(),
            Select::make('province_id')
                ->label('Province')
                ->relationship('province', 'province_name')
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function (callable $set) {
                    $set('city_id', null);
                    $set('district_id', null);
                    $set('subdistrict_id', null);
                    $set('poscode_id', null);
                }),
            Select::make('city_id')
                ->label('City')
                ->relationship('city', 'city_name')
                ->searchable()
                ->preload()
                ->reactive()
                ->options(function (callable $get) {
                    $provinceId = $get('province_id');
                    if (!$provinceId) {
                        return [];
                    }
                    return \App\Models\City::where('province_id', $provinceId)->pluck('city_name', 'id');
                })
                ->afterStateUpdated(function (callable $set) {
                    $set('district_id', null);
                    $set('subdistrict_id', null);
                    $set('poscode_id', null);
                }),
            Select::make('district_id')
                ->label('District')
                ->relationship('district', 'district_name')
                ->searchable()
                ->preload()
                ->reactive()
                ->options(function (callable $get) {
                    $cityId = $get('city_id');
                    if (!$cityId) {
                        return [];
                    }
                    return \App\Models\District::where('city_id', $cityId)->pluck('district_name', 'id');
                })
                ->afterStateUpdated(function (callable $set) {
                    $set('subdistrict_id', null);
                    $set('poscode_id', null);
                }),
            Select::make('subdistrict_id')
                ->label('Subdistrict')
                ->relationship('subdistrict', 'subdistrict_name')
                ->searchable()
                ->preload()
                ->reactive()
                ->options(function (callable $get) {
                    $districtId = $get('district_id');
                    if (!$districtId) {
                        return [];
                    }
                    return \App\Models\Subdistrict::where('district_id', $districtId)->pluck('subdistrict_name', 'id');
                })
                ->afterStateUpdated(function (callable $set) {
                    $set('poscode_id', null);
                }),
            Select::make('poscode_id')
                ->label('Postal Code')
                ->relationship('postalCode', 'poscode')
                ->searchable()
                ->preload()
                ->options(function (callable $get) {
                    $subdistrictId = $get('subdistrict_id');
                    if (!$subdistrictId) {
                        return [];
                    }
                    return \App\Models\PostalCode::where('subdistrict_id', $subdistrictId)->pluck('poscode', 'id');
                }),
            Toggle::make('status')
                ->label('Status')
                ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('code_office')
                ->label('Office Code')
                ->searchable()
                ->sortable(),
            TextColumn::make('office_name')
                ->label('Office Name')
                ->searchable()
                ->sortable(),
            TextColumn::make('vendor.vendor_name')
                ->label('Vendor')
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
            TextColumn::make('pic_name')
                ->label('PIC Name')
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->sortable(),
            TextColumn::make('phone')
                ->label('Phone')
                ->searchable()
                ->sortable(),
            BooleanColumn::make('status')
                ->label('Active')
                ->sortable(),
            TextColumn::make('creator.name')
                ->label('Created By')
                ->searchable()
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
        ])
        ->filters([
            SelectFilter::make('status')
                ->label('Status')
                ->options([
                    '1' => 'Active',
                    '0' => 'Inactive',
                ]),
            SelectFilter::make('vendor_id')
                ->label('Vendor')
                ->relationship('vendor', 'vendor_name')
                ->searchable()
                ->preload(),
            SelectFilter::make('province_id')
                ->label('Province')
                ->relationship('province', 'province_name')
                ->searchable()
                ->preload(),
            SelectFilter::make('city_id')
                ->label('City')
                ->relationship('city', 'city_name')
                ->searchable(),
            SelectFilter::make('subdistrict_id')
                ->label('Subdistrict')
                ->relationship('subdistrict', 'subdistrict_name')
                ->searchable(),
            SelectFilter::make('poscode_id')
                ->label('Postal Code')
                ->relationship('postalCode', 'poscode')
                ->searchable(),
            SelectFilter::make('created_by')
                ->label('Created By')
                ->relationship('creator', 'name')
                ->searchable()
                ->preload(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOffices::route('/'),
            'create' => Pages\CreateOffice::route('/create'),
            'edit' => Pages\EditOffice::route('/{record}/edit'),
        ];
    }
}
