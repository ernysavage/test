<?php
namespace App\Services;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    // Логика регистрации
    public function register(array $params)
    {
        $user = User::create($params);

        return $user;
    }

    public function login(array $params)
{
    //  подразумевает $params содержит 'email' и 'password'
    if (!$token = JWTAuth::attempt($params)) {
        // Если аутентификация не прошла, возвращаем ошибку
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
}

public function me()
{
    try {
        // Пытаемся получить токен из запроса
        $token = auth()->getToken()->get();
    } catch (\Exception $e) {
        // Если токен не найден или произошла ошибка – возвращаем 404
        return response()->json(['error' => 'Token not found or invalid']);
    }
    
    // Формируем уникальный ключ для кэша с использованием MD5 хэша токена
    $cacheKey = 'user_' . md5($token);

    // Пытаемся получить пользователя из кэша,
    // если его нет – функция  вернёт auth()->user()
    $user = \Cache::remember($cacheKey, now()->addMinutes(1440), function () {
        return auth()->user();
    });

    // Если пользователь не найден, возвращаем ошибку 
    if (!$user) {
        return response()->json(['error' => 'User not found for this token']);
    }
    
    return response()->json($user);
}

    public function logout()
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);

        // Удаляем данные пользователя из кэша при выходе
        Cache::forget('user_' . $token);

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $currentToken = JWTAuth::getToken();

        // Обновляем токен
        $newToken = JWTAuth::refresh($currentToken);

        return $this->respondWithToken($newToken);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
