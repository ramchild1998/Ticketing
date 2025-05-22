<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CctvResource\Pages;
use App\Filament\Resources\CctvResource\RelationManagers;
use App\Models\Area;
use App\Models\Cctv;
use App\Models\Location;
use App\Models\Machine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;

class CctvResource extends Resource
{
    protected static ?string $model = Cctv::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'CCTV';
    protected static ?string $pluralLabel = 'CCTV';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 5;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('machine_id')
                    ->label('Machine')
                    ->relationship('machine', 'machine_code')
                    ->searchable()
                    ->lazy()
                    ->options(fn () => Cache::remember('machines', 60 * 60 * 24, fn () => Machine::pluck('machine_code', 'id')->take(100)->toArray()))
                    ->required()
                    ->preload(),
                Forms\Components\Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'location_name')
                    ->searchable()
                    ->lazy()
                    ->options(fn () => Cache::remember('locations', 60 * 60 * 24, fn () => Location::pluck('location_name', 'id')->take(100)->toArray()))
                    ->required()
                    ->preload(),
                Forms\Components\Select::make('area_id')
                    ->label('Area')
                    ->relationship('area', 'name_area')
                    ->searchable()
                    ->lazy()
                    ->options(fn () => Cache::remember('areas', 60 * 60 * 24, fn () => Area::pluck('name_area', 'id')->take(100)->toArray()))
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('status_cctv')
                    ->label('Status CCTV')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('merk_dvr')
                    ->label('Merk DVR')
                    ->required()
                    ->maxLength(40),
                Forms\Components\TextInput::make('sn_dvr')
                    ->label('SN DVR')
                    ->required()
                    ->maxLength(40),
                Forms\Components\TextInput::make('kunci_ruangan')
                    ->label('Kunci Ruangan')
                    ->required()
                    ->maxLength(40),
                Forms\Components\TextInput::make('sampling_dvr')
                    ->label('Sampling DVR')
                    ->required()
                    ->maxLength(40),
                Forms\Components\DateTimePicker::make('tanggal_pengerjaan')
                    ->label('Tanggal Pengerjaan')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->label('Note')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('pkt_atm')
                    ->label('PKT ATM')
                    ->required()
                    ->maxLength(40),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Cctv::query()->with([
                    'machine' => fn ($query) => $query->select('id', 'machine_code'),
                    'location' => fn ($query) => $query->select('id', 'location_name'),
                    'area' => fn ($query) => $query->select('id', 'name_area'),
                    'creator' => fn ($query) => $query->select('id', 'name'),
                    'updater' => fn ($query) => $query->select('id', 'name'),
                ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('machine.machine_code')
                    ->label('Machine ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.location_name')
                    ->label('Location ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.name_area')
                    ->label('Area ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_cctv')
                    ->label('Status CCTV')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merk_dvr')
                    ->label('Merk DVR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sn_dvr')
                    ->label('SN DVR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kunci_ruangan')
                    ->label('Kunci Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sampling_dvr')
                    ->label('Sampling DVR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pengerjaan')
                    ->label('Tanggal Pengerjaan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pkt_atm')
                    ->label('PKT ATM')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
                    ->label('Updated By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('machine_id')
                    ->label('Machine')
                    ->relationship('machine', 'machine_code')
                    ->searchable()
                    ->options(fn () => Cache::remember('machine_filter', 60 * 60 * 24, fn () => Machine::pluck('machine_code', 'id')->take(100)->toArray())),
                Tables\Filters\SelectFilter::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'location_name')
                    ->searchable()
                    ->options(fn () => Cache::remember('location_filter', 60 * 60 * 24, fn () => Location::pluck('location_name', 'id')->take(100)->toArray())),
                Tables\Filters\SelectFilter::make('area_id')
                    ->label('Area')
                    ->relationship('area', 'name_area')
                    ->searchable()
                    ->options(fn () => Cache::remember('area_filter', 60 * 60 * 24, fn () => Area::pluck('name_area', 'id')->take(100)->toArray())),
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
            'index' => Pages\ListCctvs::route('/'),
            'create' => Pages\CreateCctv::route('/create'),
            'edit' => Pages\EditCctv::route('/{record}/edit'),
        ];
    }
}
