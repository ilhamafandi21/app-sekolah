<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
             
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record; // User yang diedit
        $role = $this->data['role']; // Role dari form
        $record->assignRole([$role]);

        Notification::make()
            ->title('User updated successfully')
            ->success()
            ->send();
    }
}
