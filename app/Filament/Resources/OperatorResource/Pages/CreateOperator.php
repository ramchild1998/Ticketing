<?php

namespace App\Filament\Resources\OperatorResource\Pages;

use App\Filament\Resources\OperatorResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOperator extends CreateRecord
{
    protected static string $resource = OperatorResource::class;

    protected static ?string $title = 'Register New Operator';

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
            ->title('Operator registered')
            ->body('The operator has been created successfully.');
    }
}
