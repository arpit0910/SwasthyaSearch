<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Admin Guard User
        Admin::firstOrCreate(
            ['email' => 'admin@arogio.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Seed Web Guard User (Fallback for Filament if default guard is used)
        User::firstOrCreate(
            ['email' => 'admin@arogio.com'],
            [
                'name' => 'Filament Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
