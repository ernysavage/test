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

// CRUD для клиентов
Route::post('clients', [ClientController::class, 'createClient']); // Создание клиента
Route::get('clients', [ClientController::class, 'listClients']); // Получить всех клиентов
Route::get('clients/{client}', [ClientController::class, 'getClient']); // Получить конкретного клиента
Route::put('clients/{client}', [ClientController::class, 'updateClient']); // Обновить клиента
Route::delete('clients/{client}', [ClientController::class, 'deleteClient']); // Удалить клиента


// attachments


Route::prefix('attachments')->group(function () {
    // Получить все вложения
    Route::get('/', [AttachmentController::class, 'getAllAttachments']);
    
    // Создать новое вложение
    Route::post('/', [AttachmentController::class, 'createAttachment']);
    
    // Получить вложение по ID
    Route::get('{attachmentUuid}', [AttachmentController::class, 'getAttachmentById']);
    
    // Обновить вложение
    Route::put('{id}', [AttachmentController::class, 'updateAttachment']);
    
    // Удалить вложение
    Route::delete('{attachmentUuid}', [AttachmentController::class, 'deleteAttachment']);
    
    // Скачать файл для пользователя
    Route::get('{attachmentId}/download', [AttachmentController::class, 'downloadByUserID']);
});


