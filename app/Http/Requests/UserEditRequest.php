<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserEditRequest extends FormRequest
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
        Validator::extend('validaemail', function($attribute, $value, $parameters){
            $campos = parent::all();
            $idd = $campos['id'];
            //procura pelo email diferente do id
            $cad = User::whereemail($value)->where('id','<>',$idd)->first();

            if (!$cad){//Não achou, ok tudo certo
                return true;
            }else //achou então da erro
                return false;
        });
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|validaemail',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Informe um nome',
            'email.required' => 'Informe um e-mail válido',
            'email.email' => 'Informe um e-mail válido',
            'email.validaemail' => 'Esse e-mail ja esta sendo usado',
        ];
    }
}
