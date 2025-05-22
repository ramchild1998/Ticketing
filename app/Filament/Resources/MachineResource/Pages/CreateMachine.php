<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Filament\Resources\MachineResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMachine extends CreateRecord
{
    protected static string $resource = MachineResource::class;

    protected static ?string $title = 'Register New Machine';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Machine registered')
            ->body('The Machine has been created successfully.');
    }
}
