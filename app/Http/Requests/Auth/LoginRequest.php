<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;


class LoginRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'email' => 'required|email',  // Электронная почта
            'password' => 'required|string',  // Пароль
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'Пароль',
            'email' => 'Электронная почта'
        ];
    }
}
