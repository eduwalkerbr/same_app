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
            "num_questao" => "required",
            "desc" => "required",
            "modelo" => "required",
            "ano" => "required",
            "disciplinas_id" => "required",
            "habilidades_id" => "required",
            "temas_id" => "required",
            "prova_gabaritos_id" => "required",
            "SAME" => "required"
        ];
    }
}
