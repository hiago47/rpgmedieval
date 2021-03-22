<?php

namespace App\Http\Controllers;

use App\Models\Batalha;
use App\Models\Jogador;
use App\Traits\ApiResponseTrait;

use Illuminate\Support\Facades\DB;

class JogadorController extends Controller
{
    use ApiResponseTrait;

    /**
     * Retorna um ranking de jogadores
     * 
     * @return array
     */
    public function ranking()
    {

        $registros = Jogador::select(DB::raw('MAX(jogadores.nickname) AS nickname, SUM(batalhas.pontos) AS pontos'))
            ->join('batalhas', 'batalhas.jogador_id', '=', 'jogadores.id')
            ->where('pontos', '>', 0)
            ->groupBy('jogadores.id')
            ->orderBy('pontos', 'DESC')
            ->orderBy('batalhas.id', 'ASC')
            ->get()
            ->toArray();

        $arr = [
            'ranking' => $registros
        ];

        return $this->jsonResponse($arr);
    }

}
