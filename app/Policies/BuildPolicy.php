<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Build;

class BuildPolicy
{
    /**
     * Determine whether the user can view any builds.
     */
   
    public function viewAny(User $user)
    {
        return true; // autorise tous les utilisateurs connectés
    }
        
    

    /**
     * Determine whether the user can view the build.
     */
    public function view(?User $user, Build $build)
    {
        // Si le build est public → tout le monde peut voir (même non connecté)
        if ($build->is_public) {
            return true;
        }
    
        // Si utilisateur connecté → vérifie droits ou s'il est propriétaire
        return $user && (
            $user->id === $build->user_id ||
            $user->hasPermission('read_build')
        );
    }
    

    /**
     * Determine whether the user can create builds.
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_build');
    }

    /**
     * Determine whether the user can update the build.
     */
    public function update(User $user, Build $build)
    {
        return $user->id === $build->user_id || $user->hasPermission('update_build');
    }

    /**
     * Determine whether the user can delete the build.
     */
    public function delete(User $user, Build $build)
    {
        return $user->id === $build->user_id || $user->hasPermission('delete_build');
    }
}
