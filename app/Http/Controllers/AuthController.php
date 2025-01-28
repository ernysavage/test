<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;  // Импортируем сервис
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $authService;

    // Инъекция зависимостей через конструктор
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Метод для регистрации пользователя
    public function register(Request $request)
    {
        $data = $request->all();
        $user = $this->authService->register($data);

        return response()->json($user, 201);
    }

    // Метод для логина пользователя
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = $this->authService->login($credentials);

        return $token;
    }

    // Метод для получения данных текущего пользователя
    public function me()
    {
        $user = $this->authService->me();

        return response()->json($user);
    }

    // Метод для выхода пользователя
    public function logout()
    {
        $message = $this->authService->logout();

        return $message;
    }
}
