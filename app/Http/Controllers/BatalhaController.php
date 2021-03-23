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


        $dano = $resAtaque['dano'];
        if($dano > 0) {
            
            $batalha->$pdvDefensor = $this->tratarInteiro(($batalha->$pdvDefensor - $dano));

            if($batalha->$pdvDefensor === 0) {
                
                $batalha->vitoria = $atacante;

                if($batalha->vitoria === 'heroi') {
                    
                    $batalha->pontos = $this->tratarInteiro((100 - count($batalha->turnos)));
                }

                $msg.= " Batalha finalizada.";
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
     * Retorna informações atuais da batalha, incluindo os turnos em ordem cronológica
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

        $res['status']['turnos'] = "Batalha não iniciada";
        if(count($batalha->turnos) > 0) {
            
            $res['status']['turnos'] = $batalha->turnos->map(function ($item, $key) {
                return [
                    'atacante' => $item->atacante,
                    'ataque' => $item->ataque,
                    'defensor' => $item->defensor,
                    'defesa' => $item->defesa, 
                    'dano' => $item->dano
                ];
            });
        }

        return $this->jsonResponse($res);
    }

    
}
