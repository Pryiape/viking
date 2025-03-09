<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes; // Assuming the model is named Classes

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a sample class
        \App\Models\Classes::create([
            'name' => 'Warrior',
            'description' => 'A strong melee fighter.',
        ]);
    }
}
