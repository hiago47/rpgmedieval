<?php

namespace App\Http\Controllers;

use App\Models\Batalha;
use App\Models\Jogador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponseTrait;
use App\Traits\PersonagensTrait;

class JogoController extends Controller
{
    use ApiResponseTrait;
    use PersonagensTrait;

    /**
     * Boas Vindas e instruções
     */
    public function index()
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
     * Registrar ou recuperar Jogador e Batalha
     */
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if($validator->fails()) {
            $response = [
                'status' => 'erro', 
                'mensagem' => 'Erro ao registrar', 'detalhes' => $validator->errors()
            ];

            return $this->jsonResponse($response, 422);
        }

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
            'mensagem' => "Você ainda está em batalha e enfrentando o MONSTRO. Continue lutando!",
            'status' => [
                'heroi' => [
                    'nome' => "JOGADOR",
                    'classe' => "CLASSE",
                    'pdv' => $batalhaEmAndamento->pdv_heroi
                ],
                'monstro' => [
                    'nome' => "MONSTRO",
                    'pdv' => $batalhaEmAndamento->pdv_monstro
                ]
            ]
        ];

        return $res;
    }


    /**
     * Regras de validação do request
     */
    public function rules()
    {
        return [
            'nickname' => [
                'required',
                'max:15',
                'min:5',
                'regex:/(^([a-zA-Z0-9]+)(\d+)?$)/u'
            ],
            'heroi'  => [
                'required',
                Rule::in($this->getHerois())
            ]
        ];
    }

    /**
     * Mensagens de erro na validação
     */
    public function messages()
    {
        return [
            'nickname.required' => 'O :attribute é obrigatório',
            'nickname.min'      => 'O :attribute deve ter no mínimo :min caracteres',
            'nickname.max'      => 'O :attribute deve ter no máximo :max caracteres',
            'nickname.regex'    => 'O :attribute não deve conter caracteres especiais',
            'heroi.required'    => 'A escolha do :attribute é obrigatória', 
            'heroi.in'          => 'Opção de :attribute inválida',
        ];
    }
}
