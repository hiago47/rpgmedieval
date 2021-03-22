<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrarJogoRequest;
use App\Models\Batalha;
use App\Models\Jogador;

use App\Traits\ApiResponseTrait;
use App\Traits\PersonagensTrait;

class JogoController extends Controller
{
    use ApiResponseTrait;
    use PersonagensTrait;

    /**
     * Boas Vindas e instruções
     * 
     * @return 
     */
    public function inicio()
    {
        
        $msg = "Seja bem vindo ao RPG Medieval. " . 
        "Voce enfrentará aleatóriamente um desses monstros: " . 

        implode(', ', $this->getMonstros('nome')) . ". " .

        "Para começar: Digite um nickname entre 5 e 15 caracteres " . 
        "e escolha uma classe de herói. " . 
        "Seu nickname pode conter letras e números, mas não é permitido espaços, acentuação e caracteres especiais.";

        $response = ['mensagem' => $msg, 'herois' => $this->listHerois()];

        return $this->jsonResponse($response);
    }
    

    /**
     * Registrar ou recuperar registro de Jogador 
     * 
     * Registrar ou recuperar Batalha em andamento
     * 
     * @param  \App\Http\Requests\RegistrarJogoRequest $request
     * @return 
     */
    public function registrar(RegistrarJogoRequest $request)
    {
        
        $nickname = $request->input('nickname');
        $heroi = $request->input('heroi');

        $jogador = Jogador::where('nickname', '=', $nickname)->first();

        if($jogador === null) {
            
            $jogador = new Jogador();
            $jogador->nickname = $nickname;
            $jogador->save();

            $response = [
                'status' => "ok", 
                'mensagem' => "Olá, {$jogador->nickname}!",
                'batalha' => $this->novaBatalha($jogador->id, $heroi)
            ];

            return $this->jsonResponse($response);
        }

        $response = [
            'status' => "ok", 
            'mensagem' => "Olá novamente, {$jogador->nickname}!",
            'batalha' => $this->buscarBatalha($jogador->id, $heroi)
        ];

        return $this->jsonResponse($response);
    }


    private function novaBatalha($jogadorId, $heroiId)
    {
        $jogador = Jogador::find($jogadorId);

        $heroi = $this->getHeroi($heroiId);
        $monstro = $this->getMonstroAleatorio();

        $batalha = new Batalha();

        $batalha->jogador_id = $jogadorId;
        $batalha->heroi_id = $heroiId;
        $batalha->pdv_heroi = $heroi->pdv;
        $batalha->monstro_id = $monstro->id;
        $batalha->pdv_monstro = $monstro->pdv;

        $batalha->save();

        $res = [
            'id' => $batalha->id,
            'mensagem' => "Você enfrentará um {$monstro->nome}. Boa sorte!",
            'status' => [
                'personagens' => [
                    'heroi' => [
                        'nome' => $jogador->nickname,
                        'classe' => $heroi->nome,
                        'pdv' => $batalha->pdv_heroi
                    ],
                    'monstro' => [
                        'nome' => $monstro->nome,
                        'pdv' => $batalha->pdv_monstro
                    ]
                ]
            ]
        ];

        return $res;
    }


    private function buscarBatalha($jogadorId, $heroiId)
    {
        $batalhaEmAndamento = Batalha::where('jogador_id', $jogadorId)
            ->whereNull('vitoria')->first();

        if($batalhaEmAndamento === null) {

            return $this->novaBatalha($jogadorId, $heroiId);
        }

        $res = [
            'id' => $batalhaEmAndamento->id,
            'mensagem' => "Você ainda está em batalha " .
            "e enfrentando o {$batalhaEmAndamento->monstro->nome}. Continue lutando!",
            'status' => [
                'personagens' => [
                    'heroi' => [
                        'nome' => $batalhaEmAndamento->jogador->nickname,
                        'classe' => $batalhaEmAndamento->heroi->nome,
                        'pdv' => $batalhaEmAndamento->pdv_heroi
                    ],
                    'monstro' => [
                        'nome' => $batalhaEmAndamento->monstro->nome,
                        'pdv' => $batalhaEmAndamento->pdv_monstro
                    ]
                ]
            ]
        ];

        return $res;
    }

}
