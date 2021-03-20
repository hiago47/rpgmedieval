<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonstros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monstros', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 15);
            $table->integer('pdv');
            $table->integer('forca');
            $table->integer('defesa');
            $table->integer('agilidade');
            $table->string('fdd', 5);
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
        Schema::dropIfExists('monstros');
    }
}
