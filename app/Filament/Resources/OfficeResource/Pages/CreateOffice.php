<?php

namespace App\Filament\Resources\OfficeResource\Pages;

use App\Filament\Resources\OfficeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOffice extends CreateRecord
{
    protected static string $resource = OfficeResource::class;

    protected static ?string $title = 'Register New Office';

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
            ->title('Office created')
            ->body('The office has been created successfully.');
    }
}
