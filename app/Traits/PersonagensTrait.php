<?php 

namespace App\Traits;

use App\Models\Heroi;
use App\Models\Monstro;

trait PersonagensTrait
{
    /**
     * Heróis
     */
    public function listHerois()
    {
        return Heroi::select(
            'id as ID',
            'nome as Nome',
            'pdv as Pontos de Vida (PdV)',
            'forca as Força',
            'defesa as Defesa',
            'agilidade as Agilidade'
        )
        ->get()->toArray();
    }

    public function getHeroi($id)
    {
        return Heroi::find($id);
    }

    public function getHerois($campo = 'id')
    {
        return Heroi::pluck($campo)->toArray();
    }

    public function getInfoHeroi($id, $campo = 'nome')
    {
        return Heroi::find($id)->$campo;
    }


    /**
     * Monstros
     */
    public function getMonstroAleatorio()
    {
        return Monstro::inRandomOrder()->first();
    }

    public function getMonstros($campo = 'id')
    {
        return Monstro::pluck($campo)->toArray();
    }
    
    public function getInfoMonstro($id, $campo = 'nome')
    {
        return Monstro::find($id)->$campo;
    }

    
}