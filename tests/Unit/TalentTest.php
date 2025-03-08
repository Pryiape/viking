<?php

namespace Tests\Unit;

use App\Models\Talent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_talent()
    {
        $talent = Talent::create([
            'name' => 'Test Talent',
            'description' => 'Description of the test talent.',
            'specialization_id' => 1,
        ]);

        $this->assertDatabaseHas('talents', [
            'name' => 'Test Talent',
        ]);
    }
}
