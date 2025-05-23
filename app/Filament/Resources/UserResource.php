<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Swis\Filament\Activitylog\Tables\Actions\ActivitylogAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('office_id')
                    ->label('Office')
                    ->relationship('office', 'office_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->visible(fn ($record) => $record === null)
                    ->revealable()
                    ->same('password_confirmation'),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->visible(fn ($record) => $record === null)
                    ->revealable(),
                TextInput::make('phone')
                    ->label('Phone Number')
                    ->required()
                    ->maxLength(255),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nip')
                ->label('NIP')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->copyable()
                ->sortable(),
            TextColumn::make('office.office_name')
                ->label('Office')
                ->searchable()
                ->sortable(),
            TextColumn::make('phone')
                ->label('Phone Number')
                ->searchable()
                ->sortable(),
            TextColumn::make('roles.name')
                ->label('Roles')
                ->searchable()
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('office_id')
                ->label('Office')
                ->relationship('office', 'office_name')
                ->searchable()
                ->preload(),
            SelectFilter::make('verified')
                ->label('Email Verification')
                ->options([
                    'verified' => 'Verified',
                    'unverified' => 'Unverified',
                ])
                ->query(function (Builder $query, array $data) {
                    if ($data['value'] === 'verified') {
                        $query->whereNotNull('email_verified_at');
                    } elseif ($data['value'] === 'unverified') {
                        $query->whereNull('email_verified_at');
                    }
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            // Tables\Actions\DeleteAction::make(),
            ActivitylogAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
