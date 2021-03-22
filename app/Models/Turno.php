<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = "turnos";

    protected $fillable = [
        "id",
        "batalha_id",
        "atacante",
        "ataque", 
        "defensor", 
        "defesa",
        "dano"
    ];

}
