<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',  // Имя пользователя
            'email' => 'required|email|unique:users,email',  // Электронная почта
            'password' => 'required|string|confirmed|min:8',  // Пароль (с подтверждением)
        ];
    }
    
    public function attributes()
    {
        return [
            'password' => 'Пароль',
            'name' => 'Имя пользователя',
            'email' => 'Электронная почта'
        ];
    }
}
