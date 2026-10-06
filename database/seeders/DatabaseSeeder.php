<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Enregistrement du super admin
        DB::table('users')->insert([
            ['name' => 'SuperAdmin', 'email' => 'bienvenuhounye05@gmail.com', 'password' => Hash::make('123456')],
        ]);
    }
}
