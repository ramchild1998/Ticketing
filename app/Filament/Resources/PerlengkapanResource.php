<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerlengkapanResource\Pages;
use App\Filament\Resources\PerlengkapanResource\RelationManagers;
use App\Models\Perlengkapan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class PerlengkapanResource extends Resource
{
    protected static ?string $model = Perlengkapan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Equipment';

    protected static ?string $pluralLabel = 'Equipment';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_alat')
                    ->label('Equipment Name')
                    ->required()
                    ->maxLength(45),
                Forms\Components\TextInput::make('jumlah')
                    ->label('Quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('satuan')
                    ->label('Unit')
                    ->required()
                    ->maxLength(15),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Description')
                    ->maxLength(45),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_alat')
                    ->label('Equipment Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->label('Unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Description')
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
                SelectFilter::make('nama_alat')
                    ->label('Equipment Name')
                    ->options(fn () => Perlengkapan::pluck('nama_alat', 'nama_alat')->toArray())
                    ->searchable(),
                Filter::make('jumlah')
                    ->form([
                        Forms\Components\TextInput::make('jumlah_min')
                            ->label('Min Quantity')
                            ->numeric(),
                        Forms\Components\TextInput::make('jumlah_max')
                            ->label('Max Quantity')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['jumlah_min'],
                                fn (Builder $query, $jumlah): Builder => $query->where('jumlah', '>=', $jumlah),
                            )
                            ->when(
                                $data['jumlah_max'],
                                fn (Builder $query, $jumlah): Builder => $query->where('jumlah', '<=', $jumlah),
                            );
                    }),
                SelectFilter::make('satuan')
                    ->label('Unit')
                    ->options(fn () => Perlengkapan::pluck('satuan', 'satuan')->toArray())
                    ->searchable(),
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
            'index' => Pages\ListPerlengkapans::route('/'),
            'create' => Pages\CreatePerlengkapan::route('/create'),
            'edit' => Pages\EditPerlengkapan::route('/{record}/edit'),
        ];
    }
}
