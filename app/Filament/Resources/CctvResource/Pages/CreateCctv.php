<?php

namespace App\Filament\Resources\CctvResource\Pages;

use App\Filament\Resources\CctvResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCctv extends CreateRecord
{
    protected static string $resource = CctvResource::class;

    protected static ?string $title = 'Register New CCTV';

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
            ->title('CCTV registered')
            ->body('The CCTV has been created successfully.');
    }
}
