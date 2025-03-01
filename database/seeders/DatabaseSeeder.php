<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',  // ✅ Assure-toi que 'name' est défini
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}

