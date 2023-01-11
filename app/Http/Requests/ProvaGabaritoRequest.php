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
            'ano' => 'required',
            'qtd' => 'required',
            'disciplinas_id' => 'required',
            'status' => 'required',
            'SAME' => 'required'
        ];
    }
}
