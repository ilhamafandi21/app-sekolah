<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TeacherResource;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

     protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // 1. Buat user baru dulu
        // dd($data['name'];
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ]);

        // 2. Tambahkan user_id ke data guru
        $data['user_id'] = $user->id;

        // 3. Hapus field yang tidak ada di tabel guru
        unset($data['user']['email'], $data['user']['password']);

        // 4. Simpan guru (teacher)
        return static::getModel()::create($data);
    }
}
