<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurmaPreviaRequest extends FormRequest
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
            "email" => "required",
            "id_escola" => "required|integer|min:0|exists:escolas,id",
            "id_turma" => "required|integer|min:0|exists:turmas,id",
            "ativo" => "required|integer|min:0",
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
            'email' => 'E-mail',
            'id_escola' => 'Escola',
            'id_turma' => 'Turma',
            'ativo' => 'Ativação',
        ];
    }
}
