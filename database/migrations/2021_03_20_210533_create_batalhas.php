<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatalhas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batalhas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jogador_id');
            $table->foreign('jogador_id')->references('id')->on('jogadores');
            $table->unsignedBigInteger('heroi_id');
            $table->foreign('heroi_id')->references('id')->on('herois');
            $table->unsignedInteger('pdv_heroi');
            $table->unsignedBigInteger('monstro_id');
            $table->foreign('monstro_id')->references('id')->on('monstros');
            $table->unsignedInteger('pdv_monstro');
            $table->enum('vitoria', ['heroi', 'monstro'])->nullable();
            $table->unsignedInteger('pontos')->default(0);
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
        Schema::dropIfExists('batalhas');
    }
}
