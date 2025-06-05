<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@viking.test'],
            [
                'name' => 'Admin',
                'password' => bcrypt('adminpassword'),
                'is_admin' => true,
            ]
        );
    }
}

