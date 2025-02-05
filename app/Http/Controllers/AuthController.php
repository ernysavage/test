<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;  // Импортируем сервис
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Middleware\JWTAuthentication;



class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware(JWTAuthentication::class)->only('me');

    }

    // Регистрация
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        
        return response()->json(['user' => $result], 201);
    }

    // Логин
    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->validated());

        return response()->json(['access_token' => $token], 200);
    }

    // Получить информацию о текущем пользователе
    public function me()
    {
        return response()->json($this->authService->me());
    }

    // Логаут
    public function logout()
    {
        return $this->authService->logout();
    }
    
    public function refresh()
    {
        return $this->authService->refresh();
    }
}