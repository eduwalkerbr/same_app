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
            'escolas_municipios_id' => 'required',
            'escolas_id' => 'required',
            'SAME' => 'required',
        ];
    }
}
