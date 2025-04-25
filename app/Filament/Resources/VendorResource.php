<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Vendors';

    protected static ?string $pluralLabel = 'Vendors';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('vendor_name')
                ->label('Vendor Name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TextInput::make('pic_name')
                ->label('PIC Name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TextInput::make('phone')
                ->label('Phone')
                ->tel()
                ->required()
                ->maxLength(255),
            Toggle::make('status')
                ->label('Active')
                ->default(false),
            // Select::make('created_by')
            //     ->label('Created By')
            //     ->relationship('creator', 'name')
            //     ->searchable()
            //     ->preload()
            //     ->disabled(fn ($livewire) => $livewire instanceof Pages\CreateVendor)
            //     ->default(auth()->id()),
            // Select::make('updated_by')
            //     ->label('Updated By')
            //     ->relationship('updater', 'name')
            //     ->searchable()
            //     ->preload()
            //     ->disabled()
            //     ->default(auth()->id())
            //     ->visible(fn ($livewire) => $livewire instanceof Pages\EditVendor),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendor_name')
                    ->label('Vendor Name')
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
                SelectFilter::make('created_by')
                    ->label('Created By')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
