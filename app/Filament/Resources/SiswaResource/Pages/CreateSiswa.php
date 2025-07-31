<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Models\User;
use Filament\Actions;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use App\Filament\Resources\SiswaResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\GenerateNis;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;


    protected function beforeCreate(): void
    {
        // Cek apakah role 'siswa' tersedia
        $role = Role::where('name', 'siswa')->where('guard_name', 'web')->first();

        if (! $role) {
            Notification::make()
                ->title('Role "siswa" belum tersedia.')
                ->body('Silakan buat role "siswa" terlebih dahulu sebelum membuat data.')
                ->danger()
                ->send();

            $this->halt(); // Hentikan proses simpan
        }
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {

        // $nis = GenerateNis::generateNis();
        //     if ($nis === null) {
        //         $this->halt(); // batalkan simpan, notifikasi sudah muncul
        //     }
        //     $data['nis'] = $nis;
       



        $user = User::create([
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ])->assignRole('siswa');// assogneRole ke Spatie

        $data['user_id'] = $user->id;

        unset($data['user']['email'], $data['user']['password']);

        return static::getModel()::create($data);
    }
}
