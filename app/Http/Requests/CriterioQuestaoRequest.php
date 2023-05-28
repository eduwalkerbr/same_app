<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriterioQuestaoRequest extends FormRequest
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
            'nome' => 'required',
            'descricao' => 'required',
            'id_disciplina' => 'required|integer|min:0|exists:disciplinas,id',
            'id_tipo_questao' => 'required|integer|min:0|exists:tipo_questaos,id',
            'ano' => 'integer|min:0'
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
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'id_disciplina' => 'Disciplina',
            'id_tipo_questao' => 'Tipo de Qqestão',
        ];
    }
}
