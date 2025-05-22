<?php

namespace App\Filament\Resources\PerlengkapanResource\Pages;

use App\Filament\Resources\PerlengkapanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePerlengkapan extends CreateRecord
{
    protected static string $resource = PerlengkapanResource::class;

    protected static ?string $title = 'Register New Equipment';

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
            ->title('Perlengkapan registered')
            ->body('The perlengkapan has been created successfully.');
    }
}
