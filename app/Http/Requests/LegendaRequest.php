<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegendaRequest extends FormRequest
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
            'titulo' => 'required',
            'descricao' => 'required',
            'cor_fundo' => 'required',
            'cor_letra' => 'required',
            'exibicao' => 'required',
            'valor_inicial' => 'required|integer|min:0',
            'valor_final' => 'required|integer|min:0',
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
            'titulo' => 'Título',
            'descricao' => 'Descrição',
            'cor_fundo' => 'Cor de Fundo',
            'cor_letra' => 'Cor de Letra',
            'exibicao' => 'Exibição',
            'valor_inicial' => 'Valor Inicial',
            'valor_final' => 'Ano',
        ];
    }
}
