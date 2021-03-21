<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponseTrait;
use App\Traits\HeroisMonstrosTrait;

class NovoJogoController extends Controller
{
    use ApiResponseTrait;
    use HeroisMonstrosTrait;
    
    /**
     * Validar
     * Registrar ou recuperar Jogador e Batalha
     */
    public function verificar(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if($validator->fails()) {
            $response = ['status' => 'erro', 'mensagem' => 'Erro ao registrar', 'detalhes' => $validator->errors()];

            return $this->jsonResponse($response, 422);
        }



        $response = ['status' => 'ok', 'mensagem' => $request->all()];

        return $this->jsonResponse($response);
    }



    /**
     * Validations
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
