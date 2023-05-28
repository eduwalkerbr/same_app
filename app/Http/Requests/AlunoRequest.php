<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlunoRequest extends FormRequest
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
            'turmas_escolas_municipios_id' => 'required|exists:municipios,id',
            'turmas_escolas_id' => 'required|exists:escolas,id',
            'turmas_id' => 'required|integer|min:0|exists:turmas,id',
            'SAME' => 'required'
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
            'turmas_escolas_municipios_id' => 'Município',
            'turmas_escolas_id' => 'Escola',
            'turmas_id' => 'Turma',
            'SAME' => 'Ano SAME',
        ];
    }
}
