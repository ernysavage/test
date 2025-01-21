<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

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
        // Валидация данных, включая файл
        $validator = Validator::make($request->all(), [
            'documentable_type' => 'required|string',
            'name' => 'required|string|max:255',
            'number_document' => 'nullable|string',
            'register_number' => 'nullable|string',
            'date_register' => 'nullable|date',
            'date_document' => 'nullable|date',
            'list_item' => 'nullable|string',
            'file' => 'required|file',  // Валидируем наличие файла
            'user_id' => 'nullable|uuid|exists:clients,id',  // Валидируем user_id (если передан)
        ]);

        // Проверяем, если валидация не прошла
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Сохраняем файл на диск
        $file = $request->file('file');
        $filePath = $file->storeAs('attachments', Str::uuid().'.'.$file->getClientOriginalExtension(), 'public');

        // Создаем новое вложение
        $attachment = new Attachment();
        $attachment->documentable_id = Str::uuid();  // Генерируем UUID для documentable_id
        $attachment->documentable_type = $request->documentable_type;
        $attachment->name = $request->name;
        $attachment->number_document = $request->number_document;
        $attachment->register_number = $request->register_number;
        $attachment->date_register = $request->date_register;
        $attachment->date_document = $request->date_document;
        $attachment->list_item = $request->list_item;
        $attachment->path_file = $filePath;
        $attachment->file_name = $file->getClientOriginalName();
        $attachment->check_sum = hash_file('sha256', $file->getPathname());

        // Если передан user_id, сохраняем его
        if ($request->has('user_id')) {
            $attachment->user_id = $request->user_id;
        }

        // Сохраняем в базе
        $attachment->save();

        return response()->json([
            'message' => 'Attachment created successfully.',
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
        // Валидация данных
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'date_document' => 'nullable|date',
            'file' => 'nullable|file|mimes:jpeg,png,pdf,docx',
            'user_id' => 'nullable|exists:clients,id',
        ]);

        // Получаем прикрепление по id
        $attachment = Attachment::findOrFail($id);

        // Обновляем атрибуты
        $attachment->update($validated);

        // Проверяем, если передан файл, то обрабатываем его
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->storeAs('attachments', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
            $attachment->path_file = $filePath;
            $attachment->file_name = $file->getClientOriginalName();
            $attachment->save();
        }

        // Возвращаем обновлённую модель
        return response()->json($attachment, 200);
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
     * Скачать файл по user_id.
     */
    public function downloadByUser($user_id)
    {
        // Найти клиента по user_id
        $client = Client::where('id', $user_id)->first();

        // Проверить, существует ли клиент
        if (!$client) {
            return response()->json(['error' => 'Client not found.'], 404);
        }

        // Проверить, истекла ли лицензия
        if (Carbon::parse($client->licence_expired_at)->isPast()) {
            return response()->json(['error' => 'License expired.'], 403);
        }

        // Найти файл по user_id в таблице attachments
        $attachment = Attachment::where('user_id', $user_id)->first();

        // Проверить, существует ли запись
        if (!$attachment) {
            return response()->json(['error' => 'Attachment not found.'], 404);
        }

        // Путь к файлу относительно хранилища
        $filePath = 'public/' . $attachment->path_file;

        // Проверить, существует ли файл на сервере
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found on server.'], 404);
        }

        // Отправить файл на скачивание
        return Storage::download($filePath, $attachment->file_name);
    }
}
