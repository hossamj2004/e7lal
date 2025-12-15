<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@e7lal.com'],
            [
                'name' => 'مدير النظام',
                'email' => 'admin@e7lal.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'phone' => '01000000000',
            ]
        );
    }
}
