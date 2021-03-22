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

    public function jogador()
    {
        return $this->belongsTo(Jogador::class);
    }

    public function heroi()
    {
        return $this->belongsTo(Heroi::class);
    }

    public function monstro()
    {
        return $this->belongsTo(Monstro::class);
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
}
