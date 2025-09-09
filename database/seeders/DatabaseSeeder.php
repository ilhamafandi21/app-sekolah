<?php

namespace Database\Seeders;

use App\Models\Biaya;
use App\Models\Day;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Tingkat;
use App\Models\Schedull;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
   #Data Roles
    public function run(): void
    {

        $roles = ['teacher', 'staff', 'siswa'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web', // sesuai guard default Anda
            ]);
        }


        #Data Master Akademik
        TahunAjaran::factory()->count(1)->create();
        Tingkat::factory()->count(3)->create();
        Jurusan::factory()->count(3)->create();
        Semester::factory()->count(2)->create();
        Subject::factory(11)->create();
        Biaya::factory()->count(5)->create();
        Schedull::factory()->count(12)->create();
        Day::factory()->count(7)->create();

        #Data Master User
        Siswa::factory(10)->create();
        Teacher::factory()->count(10)->create();

        #Data User Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ])->assignRole('admin');

    }
}
