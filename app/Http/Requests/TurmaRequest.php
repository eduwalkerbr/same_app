<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurmaRequest extends FormRequest
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
            'TURMA' => 'required',
            'DESCR_TURMA' => 'required',
            'status' => 'required',
            'municipios_id' => 'required|integer|min:0|exists:municipios,id',
            'escolas_id' => 'required|integer|min:0|exists:escolas,id',
            'SAME' => 'required',
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
            'TURMA' => 'Nome da Turma',
            'DESCR_TURMA' => 'Descrição da Turma',
            'status' => 'Status',
            'municipios_id' => 'Município',
            'escolas_id' => 'Escola',
            'SAME' => 'Ano SAME',
        ];
    }
}
