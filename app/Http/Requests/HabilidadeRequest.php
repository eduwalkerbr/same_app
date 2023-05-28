<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HabilidadeRequest extends FormRequest
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
            'desc' => 'required',
            'disciplinas_id' => 'required|integer|min:0|exists:disciplinas,id',
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
            'desc' => 'Descrição da Habilidade',
            'disciplinas_id' => 'Disciplina',
        ];
    }
}
