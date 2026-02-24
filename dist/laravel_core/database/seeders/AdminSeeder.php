<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if an admin user already exists
        if (User::where('role', 'admin')->exists()) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        User::create([
            'name' => 'Admin User',
            'email' => 'info@riyana-immobilien.de',
            'password' => Hash::make('ChangeYourPassword'),
            'role' => 'admin',
            'must_change_password' => true,
            'is_active' => true,
        ]);

        $this->command->info('Default Admin user created successfully.');
    }
}
