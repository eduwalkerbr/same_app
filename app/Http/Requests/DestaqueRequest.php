<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestaqueRequest extends FormRequest
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
            'conteudo' => 'required',
            'descricao' => 'required',
            'fonte' => 'required'
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
            'conteudo' => 'Conteúdo',
            'descricao' => 'Descrição',
            'fonte' => 'Fonte',
        ];
    }
}
