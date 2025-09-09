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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $roles = ['teacher', 'staff', 'siswa'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web', // sesuai guard default Anda
            ]);
        }

        // User::factory(2)->create();

        TahunAjaran::factory()->count(1)->create();
        Siswa::factory(10)->create();
        Tingkat::factory()->count(3)->create();
        Jurusan::factory()->count(3)->create();
        Subject::factory(11)->create();
        // Teacher::factory()->count(10)->create();
        Semester::factory()->count(2)->create();
        Biaya::factory()->count(5)->create();
        Schedull::factory()->count(12)->create();
        Day::factory()->count(7)->create();




        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ])->assignRole('admin');


        // for ($i = 1; $i <= 40; $i++) {
        //     Subject::create([
        //         'kode' => 'MP' . $i,
        //         'name' => 'Subject ' . $i,
        //         'deskripsi' => 'Deskripsi untuk subject ' . $i,
        //     ]);
        // }
    }
}
