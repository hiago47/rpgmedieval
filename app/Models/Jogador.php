<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    use HasFactory;

    protected $table = "jogadores";

    protected $fillable = [
        "id",
        "nickname"
    ];
    
    public function batalhas()
    {
        return $this->hasMany(Batalha::class);
    }
    
}
