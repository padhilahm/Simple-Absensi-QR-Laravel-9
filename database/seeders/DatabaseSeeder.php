<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(1)->create();
        \App\Models\User::factory()->create([
            'name' => 'Padhilah',
            'email' => 'padhilahm@gmail.com',
            'password' => bcrypt('password'),
            'school_name' => 'SMA Banjarbaru',
        ]);
        \App\Models\Setting::factory(1)->create();
        \App\Models\StudentClass::factory(3)->create();
        \App\Models\Student::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
