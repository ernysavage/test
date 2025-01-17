<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;


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


// attachments

Route::group(['prefix' => 'attachments', 'middleware' => ['auth:sanctum']], function () {
    Route::get('', [AttachmentController::class, 'index'])->name('get_attachments');
    Route::get('{id}', [AttachmentController::class, 'show'])->name('show_attachment');
    Route::post('', [AttachmentController::class, 'store'])->name('store_attachment');
    Route::put('{id}', [AttachmentController::class, 'update'])->name('update_attachment');
    Route::delete('{id}', [AttachmentController::class, 'destroy'])->name('delete_attachment');
    Route::get('download', [AttachmentController::class, 'download'])->name('download_attachment');
});