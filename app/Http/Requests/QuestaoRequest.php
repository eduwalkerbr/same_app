<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestaoRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "num_questao" => "required|integer|min:0",
            "desc" => "required",
            "modelo" => "required|size:1",
            "ano" => "required|integer|min:0",
            "disciplinas_id" => "required|integer|min:0|exists:disciplinas,id",
            "habilidades_id" => "required|integer|min:0|exists:habilidades,id",
            "temas_id" => "required|integer|min:0|exists:temas,id",
            "prova_gabaritos_id" => "required|integer|min:0|exists:prova_gabaritos,id",
            "SAME" => "required",
            "tipo" => "required",
            "correta" => "size:1",
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'num_questao' => 'Número da Questão',
            'desc' => 'Descrição da Questão',
            'modelo' => 'Modelo da Questão',
            'ano' => 'Ano da Questão',
            'disciplinas_id' => 'Disciplina',
            'habilidades_id' => 'Habilidade',
            'temas_id' => 'Tema',
            'prova_gabaritos_id' => 'Prova Gabarito',
            'SAME' => 'Ano SAME',
            'tipo' => 'Tipo da Questão',
            'correta' => 'Resposta Correta',
        ];
    }
}
