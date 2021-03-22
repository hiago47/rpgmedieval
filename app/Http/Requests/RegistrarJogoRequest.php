<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Traits\PersonagensTrait;

class RegistrarJogoRequest extends FormRequest
{
    use PersonagensTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Regras de validação.
     *
     * @return array
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
            'heroi' => [
                'required',
                Rule::in($this->getHerois())
            ]
        ];
    }

    /**
     * Mensagens de erro na validação.
     *
     * @return array
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
