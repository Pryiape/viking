<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildTalentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('build_talent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('build_id')->constrained()->onDelete('cascade');
            $table->foreignId('talent_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['build_id', 'talent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('build_talent');
    }
}
