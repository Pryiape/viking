<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('profile_picture')->nullable(); // champ ajouté
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('utilisateur'); // champ rôle par défaut
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
    
        // Supprimer d’abord builds si elle existe
        Schema::dropIfExists('builds');
    
        // Ensuite seulement on supprime users
        Schema::dropIfExists('users');
    
        Schema::enableForeignKeyConstraints();
    }
};
