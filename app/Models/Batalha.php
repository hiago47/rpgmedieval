<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batalha extends Model
{
    use HasFactory;

    protected $table = "batalhas";

    protected $fillable = [
        "id",
        "jogador_id",
        "heroi_id",
        "pdv_heroi",
        "monstro_id",
        "pdv_monstro",
        "vitoria",
        "pontos"
    ];

}
