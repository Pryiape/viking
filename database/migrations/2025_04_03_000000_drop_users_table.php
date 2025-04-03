<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUsersTable extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        // Supprimer les foreign keys si elles existent
        Schema::table('permission_user', function (Blueprint $table) {
            if (Schema::hasColumn('permission_user', 'user_id')) {
                $table->dropForeign('permission_user_user_id_foreign');
                $table->dropColumn('user_id');
            }
        });

        Schema::table('builds', function (Blueprint $table) {
            if (Schema::hasColumn('builds', 'user_id')) {
                $table->dropForeign('builds_user_id_foreign');
                $table->dropColumn('user_id');
            }
        });

        // Supprimer la table users
        Schema::dropIfExists('users');

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
