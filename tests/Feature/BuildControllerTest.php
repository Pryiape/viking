<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Build;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_builds()
    {
        // Créer un utilisateur unique et l'authentifier
        $user = User::factory()->create();
        $user->assignRole('user'); // Assigner le rôle utilisateur

        $this->actingAs($user);

        $response = $this->get('/builds');

        $response->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_build()
    {
        // Créer un utilisateur unique et l'authentifier
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user);

        // Création du build
        $response = $this->post('/builds', [
            'name' => 'New Build',
            'description' => 'Build description',
            'user_id' => $user->id, // Associer le build à l'utilisateur
        ]);

        // Vérifier que l'utilisateur est redirigé après la création du build
        $response->assertRedirect('/builds');

        // Vérifier que le build est bien en base de données
        $this->assertDatabaseHas('builds', [
            'name' => 'New Build',
            'description' => 'Build description',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function an_authenticated_user_can_delete_a_build()
    {
        // Créer un utilisateur unique et l'authentifier
        $user = User::factory()->create();
        $user->assignRole('user');

        // Créer un build pour cet utilisateur
        $build = Build::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // Suppression du build
        $response = $this->delete('/builds/' . $build->id);

        // Vérifier que l'utilisateur est bien redirigé après suppression
        $response->assertRedirect('/builds');

        // Vérifier que le build a bien été supprimé
        $this->assertDatabaseMissing('builds', ['id' => $build->id]);
    }
}
