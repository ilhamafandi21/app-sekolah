<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TeacherResource;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
             Actions\Action::make('assignGuruRole')
                ->label('Tetapkan Role Guru')
                ->visible(fn ($record) => !$record->user?->hasRole('teacher'))
                ->action(function ($record) {
                    if ($record->user) {
                        $record->user->assignRole('teacher');
                        Notification::make()
                            ->title('Role guru berhasil ditetapkan!')
                            ->success()
                            ->send();
                    }
                })
                ->color('success'),  
        ];
    }
}
