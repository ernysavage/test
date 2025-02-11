<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthentication
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('me')) { // Проверяем маршрут /me
            try {
                // Проверка на наличие токена
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof TokenExpiredException) {
                    $newToken = JWTAuth::parseToken()->refresh();
                    return response()->json(['success' => false, 'token' => $newToken, 'status' => 'Expired'], 200);
                } else if ($e instanceof TokenInvalidException) {
                    return response()->json(['success' => false, 'message' => 'Token invalid'], 401);
                } else {
                    return response()->json(['success' => false, 'message' => 'Token not found'], 401);
                }
            }
            // Если аутентификация прошла успешно, пропускаем запрос дальше
            return $next($request);
        }
        
        try {
            // Для остальных маршрутов проверяем наличие токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenExpiredException) {
                $newToken = JWTAuth::parseToken()->refresh();
                return response()->json(['success' => false, 'token' => $newToken, 'status' => 'Expired'], 200);
            } else if ($e instanceof TokenInvalidException) {
                return response()->json(['success' => false, 'message' => 'Token invalid'], 401);
            } else {
                return response()->json(['success' => false, 'message' => 'Token not found'], 401);
            }
        }

        return $next($request);
    }
}
