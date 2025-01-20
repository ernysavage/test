<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AttachmentController extends Controller
{
    /**
     * Получить все вложения для клиента.
     */
    public function index()
    {
        $attachments = Attachment::all();
    
    // Возвращаем их в формате JSON
    return response()->json($attachments);
    }

    /**
     * Создать новое вложение.
     */
    

     public function store(Request $request)
    {
        // Валидация данных с учётом всех полей
        $validated = $request->validate([
            'documentable_type' => 'required|string',
            'name' => 'required|string|max:255',
            'number_document' => 'nullable|string',
            'register_number' => 'nullable|string',
            'date_register' => 'nullable|date',
            'date_document' => 'nullable|date',
            'list_item' => 'nullable|string',
            'file' => 'required|file',
            'user_id' => 'nullable|uuid|exists:clients,id',  // Проверка существования user_id в таблице clients
        ]);

        // Проверяем, если передан user_id, валидируем его
        if (isset($validated['user_id'])) {
            $user = Client::find($validated['user_id']);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
        }

        // Генерация уникального documentable_id
        $documentable_id = Str::uuid(); // Генерируем UUID для documentable_id

        // Сохраняем файл на диск
        $file = $request->file('file');
        $filePath = $file->storeAs('attachments', Str::uuid().'.'.$file->getClientOriginalExtension(), 'public');

        // Создаем новое вложение
        $attachment = new Attachment();
        $attachment->documentable_id = $documentable_id; // Устанавливаем автоматически сгенерированный UUID
        $attachment->documentable_type = $validated['documentable_type'];
        $attachment->name = $validated['name']; // Имя файла будет использовано как имя документа
        $attachment->number_document = $validated['number_document'];
        $attachment->register_number = $validated['register_number'];
        $attachment->date_register = $validated['date_register'];
        $attachment->date_document = $validated['date_document'];
        $attachment->list_item = $validated['list_item'];
        $attachment->path_file = $filePath;  // Сохраняем путь к файлу
        $attachment->file_name = $file->getClientOriginalName(); // Имя файла, которое будет сохранено
        $attachment->check_sum = hash_file('sha256', $file->getPathname());

        // Если передан внешний ключ на клиента (user_id), сохраняем его
        if (isset($validated['user_id'])) {
            $attachment->user_id = $validated['user_id'];  // Присваиваем user_id
        }

        $attachment->save();

        return response()->json([
            'message' => 'Attachment created successfully',
            'attachment' => $attachment
        ], 201);
    }



    /**
     * Показать конкретное вложение.
     */
    public function show($attachmentUuid)
    {
        $attachment = Attachment::where('id', $attachmentUuid)->firstOrFail();
        return response()->json($attachment);
    }

    /**
     * Обновить данные вложения.
     */
    public function update(Request $request, $id)
{
    // Получаем прикрепление по id
    $attachment = Attachment::findOrFail($id);

    // Валидация данных
    $validated = $request->validate([
        'name' => 'nullable|string|max:255',
        // Добавьте другие поля, которые вы хотите обновлять
    ]);

    // Обновление атрибутов, если они были переданы
    foreach ($validated as $key => $value) {
        $attachment->$key = $value;
    }

    // Сохраняем обновление
    $attachment->save();

    // Возвращаем обновленную модель
    return response()->json($attachment);
}



    /**
     * Удалить вложение.
     */
    public function destroy($attachmentUuid)
    {
        $attachment = Attachment::where('id', $attachmentUuid)->firstOrFail();

        // Удаляем файл с диска
        Storage::delete('public/'.$attachment->path_file);

        // Удаляем запись из базы данных
        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted successfully'], 204);
    }

    /**
     * Скачать файл.
     */
    public function download($clientId)
{
    // Поиск клиента по ID
    $client = Client::findOrFail($clientId);

    // Проверка срока действия лицензии
    if (now()->greaterThanOrEqualTo($client->licence_expired_at)) {
        return response()->json(['error' => 'license_expired'], 403);
    }

    // Поиск вложения, связанного с клиентом
    $attachment = Attachment::where('documentable_id', $clientId)
                            ->where('documentable_type', 'Client')
                            ->firstOrFail();

    // Путь к файлу
    $filePath = storage_path('app/public/' . $attachment->path_file);

    // Проверка существования файла
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'file_not_found'], 404);
    }

    // Возвращение файла для скачивания
    return response()->download($filePath);
}

}
// 50d2660b-f63b-4288-bf7c-c56cc5e45840