<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvaGabaritoRequest extends FormRequest
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
            'DESCR_PROVA' => 'required',
            'gabarito' => 'required',
            'ano' => 'required|integer|min:0',
            'qtd' => 'required|integer|min:0',
            'disciplinas_id' => 'required|integer|min:0|exists:disciplinas,id',
            'status' => 'required|integer|min:0',
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
            'DESCR_PROVA' => 'Descrição da Prova',
            'gabarito' => 'Gabarito',
            'ano' => 'Ano',
            'qtd' => 'Quantidade',
            'SAME' => 'Ano SAME',
            'disciplinas_id' => 'Disciplina',
        ];
    }
}
