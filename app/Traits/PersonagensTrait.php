<?php 

namespace App\Traits;

use App\Models\Heroi;
use App\Models\Monstro;

trait PersonagensTrait
{
    /**
     * Lista dos Heróis com os atributos 
     * 
     * @return array
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

    /**
     * Retorna registro do Heróis pelo id
     * 
     * @param int $id
     * @return Heroi
     */
    public function getHeroi($id)
    {
        return Heroi::find($id);
    }

    /**
     * Array de Heróis em um campo específico, GROUP_CONCAT('campo')
     * 
     * @param string $campo
     * @return array
     */
    public function getHerois($campo = 'id')
    {
        return Heroi::pluck($campo)->toArray();
    }


    /**
     * Retorna um único monstro aletarório
     * 
     * @return Monstro
     */
    public function getMonstroAleatorio()
    {
        return Monstro::inRandomOrder()->first();
    }

    /**
     * Array de Monstros em um campo específico, GROUP_CONCAT('campo')
     * 
     * @param string $campo
     * @return array
     */
    public function getMonstros($campo = 'id')
    {
        return Monstro::pluck($campo)->toArray();
    }
    
}