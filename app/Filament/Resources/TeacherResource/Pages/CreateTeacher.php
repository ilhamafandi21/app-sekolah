<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Models\User;
use Filament\Actions;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TeacherResource;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     if (! Role::where('name', 'teacher')->where('guard_name', 'web')->exists()) {
    //             Notification::make()
    //                 ->title('Role teacher tidak ditemukan')
    //                 ->body('Role "teacher" belum tersedia. Silakan buat role terlebih dahulu.')
    //                 ->danger()
    //                 ->send();   
    //             }

    //      return $data; // atau kembalikan error lainnya
    // }


    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // 1. Buat user baru dulu
        // dd($data['name'];
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ]); //->assignRole('teacher') assogneRole ke Spatie

        // 2. Tambahkan user_id ke data guru
        $data['user_id'] = $user->id;

        // 3. Hapus field yang tidak ada di tabel guru
        unset($data['user']['email'], $data['user']['password']);

        // 4. Simpan guru (teacher)
        return static::getModel()::create($data);
    }
}
