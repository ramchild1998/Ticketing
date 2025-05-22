<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Filament\Resources\MachineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMachines extends ListRecords
{
    protected static string $resource = MachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Register New Machine')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->size('lg')
                ->iconPosition('before'),
        ];
    }
}
