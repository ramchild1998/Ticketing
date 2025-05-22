<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Filament\Resources\CardResource\RelationManagers;
use App\Models\Card;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Cards';

    protected static ?string $pluralLabel = 'Cards';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('card_no')
                    ->label('Card Number')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('jenis_kartu')
                    ->label('Jenis Kartu')
                    ->required()
                    ->maxLength(45),
                Forms\Components\TextInput::make('co_brand')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('brand_code')
                    ->label('Brand Code')
                    ->maxLength(10),
                Forms\Components\TextInput::make('kartu_baru')
                    ->label('Kartu Baru')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sisa_hopper')
                    ->label('Sisa Hopper')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->label('Total Hopper')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('expired_dates')
                    ->label('Expired Date')
                    ->placeholder('MM-YY')
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->required()
                    ->maxLength(45),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('card_no')
                    ->label('Card Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kartu')
                    ->label('Jenis Kartu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('co_brand')
                    ->label('Co-Brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand_code')
                    ->label('Brand Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kartu_baru')
                    ->label('Kartu Baru')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sisa_hopper')
                    ->label('Sisa Hopper')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Hopper')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expired_dates')
                    ->label('Expired Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
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
                Tables\Columns\TextColumn::make('created_by')
                    ->label('Created By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->label('Updated By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
}
