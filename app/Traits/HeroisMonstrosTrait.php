<?php 

namespace App\Traits;

use App\Models\Heroi;
use App\Models\Monstro;

trait HeroisMonstrosTrait
{
    
    public function getAllHerois()
    {
        return Heroi::select(
            'id as ID',
            'nome as Nome',
            'pdv as Pontos de Vida',
            'forca as Força',
            'defesa as Defesa',
            'agilidade as Agilidade'
        )
        ->get()->toArray();
    }

    public function getHerois($campo = 'id')
    {
        return Heroi::pluck($campo)->toArray();
    }

    public function getMonstros($campo = 'id')
    {
        //Monstro::select(DB::raw('GROUP_CONCAT(nome SEPARATOR \', \') as nome'))->first()->$campo;
        return Monstro::pluck($campo)->toArray();
    }

    
}