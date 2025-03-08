<?php

namespace Tests\Unit;

use App\Models\Specialization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecializationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_specialization()
    {
        $specialization = Specialization::create([
            'name' => 'Test Specialization',
            'description' => 'Description of the test specialization.',
            'class_id' => 1,
        ]);

        $this->assertDatabaseHas('specializations', [
            'name' => 'Test Specialization',
        ]);
    }
}
