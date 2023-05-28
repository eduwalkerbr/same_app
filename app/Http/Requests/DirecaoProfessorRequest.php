<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirecaoProfessorRequest extends FormRequest
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
            'id_previlegio' => 'required|integer|min:0|exists:previlegios,id',
            'escolas_id' => 'required',
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
            'id_previlegio' => 'Previlégio',
            'escolas_id' => 'Escola',
            'SAME' => 'Ano SAME'
        ];
    }
}
