<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batalha_id');
            $table->foreign('batalha_id')->references('id')->on('batalhas');
            $table->enum('atacante', ['heroi', 'monstro']);
            $table->unsignedInteger('ataque');
            $table->unsignedInteger('defesa');
            $table->unsignedInteger('dano');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos');
    }
}
