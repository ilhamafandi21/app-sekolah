<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Jurusan;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(2)->create();
        // Siswa::factory(25)->create();
        Jurusan::factory(3)->create();
        // Subject::factory(15)->create();
        Teacher::factory()->count(10)->create();
        Semester::factory()->count(4)->create();


        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ])->assignRole('admin');

        
        for ($i = 1; $i <= 40; $i++) {
            Subject::create([
                'kode' => 'MP' . $i,
                'name' => 'Subject ' . $i,
                'deskripsi' => 'Deskripsi untuk subject ' . $i,
            ]);
        }
    }
}
