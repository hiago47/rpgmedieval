<?php

namespace App\Http\Controllers;

use App\Models\Batalha;
use App\Models\Turno;
use App\Traits\BatalhaTrait;
use App\Traits\ApiResponseTrait;

class BatalhaController extends Controller
{
    use BatalhaTrait;
    use ApiResponseTrait;

    /**
     * Inicia ou continua uma batalha
     * 
     * @param Batalha $batalha;
     * @return 
     */
    public function jogar(Batalha $batalha)
    {

        if(!is_null($batalha->vitoria)) {
            $res = [
                'id' => $batalha->id,
                'mensagem' => "Esta batalha já foi finalizada.",
                'status' => $this->status($batalha)
            ];

            return $this->jsonResponse($res);
        }

        $atacante = $this->iniciativa($batalha->heroi, $batalha->monstro);
        $defensor = $this->defensor($atacante);

        $resAtaque = $this->ataque($batalha->$atacante, $batalha->$defensor);
        $resAtaque['atacante'] = $atacante;
        $resAtaque['defensor'] = $defensor;

        $pdvDefensor = '';
        if($atacante === 'heroi') {

            $pdvDefensor = 'pdv_monstro';
            $msg = "Você atacou o {$batalha->monstro->nome}.";
        }
        else if($atacante === 'monstro') {

            $pdvDefensor = 'pdv_heroi';
            $msg = "O {$batalha->monstro->nome} atacou você.";
        }

        $turno = new Turno($resAtaque);
        $batalha->turnos()->save($turno);

        $danoSofrido = 0;
        if($resAtaque['dano'] > 0) {

            $danoSofrido = $resAtaque['dano'];
            $batalha->$pdvDefensor-= $danoSofrido;
            
            if($batalha->$pdvDefensor < 0) {
                $batalha->$pdvDefensor = 0;
            }
        }

        if($danoSofrido > 0) {
            if($batalha->$pdvDefensor === 0) {
                $batalha->vitoria = $atacante;
                $msg.= " Batalha finalizada.";

                if($batalha->vitoria === 'heroi') {
                    $batalha->pontos = 100 - count($batalha->turnos);

                    if($batalha->pontos < 0) {
                        $batalha->pontos = 0;
                    }
                }
            }

            $batalha->save();
        }

        $res = [
            'id' => $batalha->id,
            'mensagem' => $msg,
            'ataque' => $resAtaque,
            'status' => $this->status($batalha)
        ];

        return $this->jsonResponse($res);
    }


    /**
     * Retorna informações atuais da batalha
     * 
     * @param Batalha $batalha;
     * @return 
     */
    public function info(Batalha $batalha)
    {
        $res = [
            'id' => $batalha->id,
            'status' => $this->status($batalha)
        ];
        $res['ultimo_ataque'] = $batalha->turnos->last()->only(
            [
                'atacante',
                'ataque',
                'defensor',
                'defesa', 
                'dano'
            ]
        );

        return $this->jsonResponse($res);
    }

    
}
