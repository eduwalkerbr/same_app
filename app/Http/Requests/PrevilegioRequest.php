<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrevilegioRequest extends FormRequest
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
            'status' => 'required|integer|min:0',
            'funcaos_id' => 'required|integer|min:0|exists:funcaos,id',
            'municipios_id' => 'required|integer|min:0|exists:municipios,id',
            'users_id' => 'required|integer|min:0|exists:users,id',
            'autorizou_users_id' => 'required|integer|min:0|exists:users,id',
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
            'status' => 'Status',
            'funcaos_id' => 'Função',
            'municipios_id' => 'Município',
            'users_id' => 'Usuário',
            'autorizou_users_id' => 'Usuário de Autorização',
        ];
    }
}
