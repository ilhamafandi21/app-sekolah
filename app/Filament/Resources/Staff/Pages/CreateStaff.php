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

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ])->assignRole('staff');// assogneRole ke Spatie

        $data['user_id'] = $user->id;

        unset($data['user']['email'], $data['user']['password']);

        return static::getModel()::create($data);
    }
}
