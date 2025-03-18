<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Build;

class BuildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Build::create([
            'user_id' => 1, // Assuming a user with ID 1 exists
            'name' => \Faker\Factory::create()->sentence(3), // Generate a random name
            'description' => \Faker\Factory::create()->text(50) . " " . now()->format('d/m/Y') // Generate a random description and append the date


        ]);
    }
}
