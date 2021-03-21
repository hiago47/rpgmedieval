<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Heroi;
use App\Models\Monstro;

use App\Traits\ApiResponseTrait;
use App\Traits\HeroisMonstrosTrait;

class BatalhaController extends Controller
{
    use ApiResponseTrait;
    use HeroisMonstrosTrait;

    /**
     * Boas Vindas e instruções
     */
    public function index()
    {
        
        $msg = "Seja bem vindo ao RPG Medieval. " . 
        "Voce enfrentará aleatóriamente um desses monstros: " . 

        implode(', ', $this->getMonstros('nome')) . ". " .

        "Para começar: Digite um nickname entre 5 e 15 caracteres (sem espaços ou caracteres especiais) " .
        "e escolha um tipo de herói. ";

        $response = ['mensagem' => $msg, 'herois' => $this->getAllHerois()];

        return $this->jsonResponse($response);
    }

    







    
}
