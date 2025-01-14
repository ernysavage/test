<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Группа маршрутов для аутентификации
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

// CRUD для клиентов
Route::post('clients', [ClientController::class, 'store']); // Создание клиента
Route::get('clients', [ClientController::class, 'index']); // Получить всех клиентов
Route::get('clients/{client}', [ClientController::class, 'show']); // Получить конкретного клиента
Route::put('clients/{client}', [ClientController::class, 'update']); // Обновить клиента
Route::delete('clients/{client}', [ClientController::class, 'destroy']); // Удалить клиента
