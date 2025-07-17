<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Models\User;
use Filament\Actions;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use App\Filament\Resources\SiswaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

   protected function mutateFormDataBeforeCreate(array $data): array
{
    if (!Role::where('name', 'siswa')->where('guard_name', 'web')->exists()) {
        Notification::make()
            ->title('Role siswa tidak ditemukan')
            ->body('Role "siswa" belum tersedia. Silakan buat role terlebih dahulu.')
            ->danger()
            ->send();

        // Hentikan proses simpan dengan melempar exception
        // throw new \Exception('Gagal menyimpan. Role "siswa" belum tersedia.');
        $this->halt(); // ⛔️ stop simpan, tanpa error
    }

    return $data;
}



    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
       
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ])->assignRole('siswa'); // assogneRole ke Spatie

        $data['user_id'] = $user->id;


        unset($data['user']['email'], $data['user']['password']);

        return static::getModel()::create($data);
    }
}
