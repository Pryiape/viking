<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function it_can_retrieve_builds()
    {
        $user = User::factory()->create();
        $build = $user->builds()->create([
            'name' => 'Test Build',
            'description' => 'Description of the test build.',
        ]);

        $this->assertCount(1, $user->builds);
    }
}
