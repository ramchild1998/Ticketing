<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineResource\Pages;
use App\Filament\Resources\MachineResource\RelationManagers;
use App\Models\Machine;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationLabel = 'Machine';
    protected static ?string $pluralLabel = 'Machines';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 6;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('machine_code')
                    ->label('Machine Code')
                    ->required()
                    ->maxLength(6),
                Forms\Components\Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'location_name')
                    ->searchable()
                    ->lazy()
                    ->options(fn () => Cache::remember('locations', 60 * 60 * 24, fn () => Location::pluck('location_name', 'id')->take(100)->toArray()))
                    ->required()
                    ->preload(),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Machine::query()->with([
                    'location' => fn ($query) => $query->select('id', 'location_name'),
                    'creator' => fn ($query) => $query->select('id', 'name'),
                    'updater' => fn ($query) => $query->select('id', 'name'),
                ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('machine_code')
                    ->label('Machine Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.location_name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'location_name')
                    ->searchable()
                    ->options(fn () => Cache::remember('location_filter', 60 * 60 * 24, fn () => Location::pluck('location_name', 'id')->take(100)->toArray())),
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
            'index' => Pages\ListMachines::route('/'),
            'create' => Pages\CreateMachine::route('/create'),
            'edit' => Pages\EditMachine::route('/{record}/edit'),
        ];
    }
}
