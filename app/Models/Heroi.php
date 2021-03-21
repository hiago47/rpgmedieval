<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heroi extends Model
{
    use HasFactory;

    protected $table = "herois";

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
