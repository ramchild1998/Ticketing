<?php

namespace App\Filament\Resources\CctvResource\Pages;

use App\Filament\Resources\CctvResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCctvs extends ListRecords
{
    protected static string $resource = CctvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Register New CCTV'),
        ];
    }
}
