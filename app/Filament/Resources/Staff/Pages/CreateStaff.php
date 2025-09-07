<?php

namespace App\Filament\Resources\Staff\Pages;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Staff\StaffResource;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function beforeCreate(): void
    {
        // Cek apakah role 'staff' tersedia
        $role = Role::where('name', 'staff')->where('guard_name', 'web')->first();

        if (! $role) {
            Notification::make()
                ->title('Role "staff" belum tersedia.')
                ->body('Silakan buat role "staff" terlebih dahulu sebelum membuat data.')
                ->danger()
                ->send();

            $this->halt(); // Hentikan proses simpan
        }
    }

    protected function handleRecordCreation(array $data): Model
    {

        dd($data);

        $existingUser = User::where('email', $data['email'])->exists();

        if ($existingUser) {
            Notification::make()
                ->title('Email sudah digunakan.')
                ->body('Silakan gunakan email lain yang belum terdaftar.')
                ->danger()
                ->send();

            $this->halt(); // Hentikan proses simpan
        }else{
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ])->assignRole('staff');// assogneRole ke Spatie


        }

            $data['user_id'] = $user->id;
            unset($data['email'], $data['password']);

        return static::getModel()::create($data);
    }
}
