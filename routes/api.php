<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;




Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::group([
    'middleware' => ['api', 'jwtAuth'],
    'prefix' => 'auth'
], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

Route::post('clients', [ClientController::class, 'createClient']); // Создание клиента
Route::get('clients', [ClientController::class, 'listClients']); // Получить всех клиентов
Route::get('clients/{client_id}', [ClientController::class, 'getClientById']); // Получить конкретного клиента
Route::put('clients/{client_id}', [ClientController::class, 'updateClient']); // Обновить клиента
Route::delete('clients/{client_id}', [ClientController::class, 'deleteClient']);


Route::prefix('attachments')->group(function () {
    // Получить все вложения
    Route::get('/', [AttachmentController::class, 'getAllAttachments']);
    
    // Создать новое вложение
    Route::post('/', [AttachmentController::class, 'createAttachment']);
    
    // Получить вложение по ID
    Route::get('{attachment_id}', [AttachmentController::class, 'getAttachmentById']);
    
    // Обновить вложение
    Route::put('{attachment_id}', [AttachmentController::class, 'updateAttachment']);
    
    // Удалить вложение
    Route::delete('{attachment_id}', [AttachmentController::class, 'deleteAttachment']);
    
    // Скачать файл для пользователя
    Route::get('{user_id}/download', [AttachmentController::class, 'downloadByUserID']);
});


