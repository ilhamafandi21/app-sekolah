<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Illuminate\Support\Facades\Hash;
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

            Actions\Action::make('resetPassword')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('danger')
                ->form([
                    Forms\Components\TextInput::make('new_password')
                        ->label('Password Baru')
                        ->password()
                        ->required()
                        ->minLength(6),
                ])
                ->action(function (array $data, $record) {
                    $record->update([
                        'password' => Hash::make($data['new_password']),
                    ]);

                    Notification::make()
                        ->title('Berhasil')
                        ->body('Password berhasil direset.')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation(false),
        ];
    }
}
