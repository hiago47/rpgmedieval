<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monstro extends Model
{
    use HasFactory;

    protected $table = "monstros";

    protected $fillable = [
        "id",
        "nome",
        "pdv",
        "forca",
        "defesa",
        "agilidade",
        "fdd",
    ];
    
}
