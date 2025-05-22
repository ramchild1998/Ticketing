<?php

namespace App\Filament\Resources\CctvResource\Pages;

use App\Filament\Resources\CctvResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCctv extends EditRecord
{
    protected static string $resource = CctvResource::class;

    protected static ?string $title = 'Edit CCTV';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('CCTV updated')
            ->body('The CCTV has been updated successfully.');
    }
}
