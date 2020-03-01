<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPassRequest extends FormRequest
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
            'id' => 'required',
            'password' => 'required|string|min:3|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Usuario não identificado',
            'password.required' => 'Informe uma senha',
            'password.min' => 'Informe uma senha com mais de 3 caracteres',
            'password.confirmed' => 'Senha não confere, digite a senha e confirme',
        ];
    }
}
