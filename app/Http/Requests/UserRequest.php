<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Informe um nome',
            'email.required' => 'Informe um e-mail válido',
            'email.email' => 'Informe um e-mail válido',
            'email.unique' => 'Este e-mail já está em uso',
            'password.required' => 'Informe uma senha',
            'password.min' => 'Informe uma senha com mais de 3 caracteres',
            'password.confirmed' => 'Senha não confere, digite a senha e confirme',
        ];
    }
}
