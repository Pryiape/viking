<?php

namespace Database\Factories;

use App\Models\Build;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildFactory extends Factory
{
    protected $model = Build::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
