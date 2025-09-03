<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TeacherResource;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;
    protected static ?string $title = 'Edit Data Guru';


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('')
                ->icon('heroicon-o-trash')
                ->modalHeading('Hapus Guru'),
             Action::make('assignGuruRole')
                ->label('Tetapkan Role Guru')
                ->visible(fn ($record) => !$record->user?->hasRole('teacher'))
                ->action(function ($record) {
                    // Cek apakah role "teacher" tersedia
                    $role = Role::where('name', 'teacher')->where('guard_name', 'web')->first();

                    if (! $role) {
                        Notification::make()
                            ->title('Role "teacher" belum ditemukan')
                            ->body('Silakan buat role "teacher" terlebih dahulu sebelum menetapkan.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Jika user ada dan role tersedia, tetapkan role
                    if ($record->user) {
                        $record->user->assignRole($role->name);
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
