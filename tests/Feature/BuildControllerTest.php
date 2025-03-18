<?php

namespace Tests\Feature;

use App\Models\Build;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_name_exceeding_100_characters_transfers_to_description()
    {
        $name = str_repeat('A', 150); // Name with 150 characters
        $response = $this->post('/builds', [
            'name' => $name,
            'description' => 'Initial description.'
        ]);

        $response->assertRedirect(route('builds.index'));
        $this->assertDatabaseHas('builds', [
            'name' => substr($name, 0, 100), // First 100 characters in name
            'description' => 'Initial description. ' . substr($name, 100) . ' ' . now()->format('d/m/Y')
        ]);
    }

    public function test_valid_name_can_be_stored()
    {
        $response = $this->post('/builds', [
            'name' => 'Valid Name',
            'description' => 'A valid description.'
        ]);

        $response->assertRedirect(route('builds.index'));
        $this->assertDatabaseHas('builds', [
            'name' => 'Valid Name'
        ]);
    }

    public function test_name_too_short_is_rejected()
    {
        $response = $this->post('/builds', [
            'name' => '1234', // Invalid: too short
            'description' => 'A valid description.'
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_too_long_is_rejected()
    {
        $response = $this->post('/builds', [
            'name' => str_repeat('A', 101), // Invalid: too long
            'description' => 'A valid description.'
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_empty_description_is_set_correctly()
    {
        $response = $this->post('/builds', [
            'name' => 'Valid Name',
            'description' => ''
        ]);

        $response->assertRedirect(route('builds.index'));
        $this->assertDatabaseHas('builds', [
            'name' => 'Valid Name',
            'description' => 'pas d\'information ' . now()->format('d/m/Y')
        ]);
    }
}
