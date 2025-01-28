<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttachmentService
{
    /**
     * Получить все вложения.
     */
    public function getAllAttachments()
    {
        return Attachment::all();
    }

    /**
     * Создать новое вложение.
     */
    public function createAttachment($request)
    {
        // Валидация данных
        $validated = $request->validate([
            'documentable_type' => 'required|string',
            'name' => 'required|string|max:255',
            'file' => 'required|file',
            'user_id' => 'nullable|uuid|exists:clients,id',
        ]);

        // Проверка на уникальность имени для пользователя
        if (Attachment::where('user_id', $request->user_id)
            ->where('name', $request->name)
            ->exists()) {
            return response()->json(['error' => 'User already has an attachment with this name'], 400);
        }

        // Сохраняем файл
        $file = $request->file('file');
        $filePath = $file->storeAs(config('app.attachment_path'), Str::uuid().'.'.$file->getClientOriginalExtension(), 'public');

        // Создаем вложение
        $attachment = Attachment::create([
            'documentable_type' => $request->documentable_type,
            'name' => $request->name,
            'number_document' => $request->number_document,
            'register_number' => $request->register_number,
            'date_register' => $request->date_register,
            'date_document' => $request->date_document,
            'list_item' => $request->list_item,
            'path_file' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'check_sum' => hash_file('sha256', $file->getPathname()),
            'user_id' => $request->user_id,
        ]);

        return response()->json(['message' => 'Attachment created successfully.', 'attachment' => $attachment], 201);
    }

    /**
     * Получить вложение по ID.
     */
    public function getAttachmentById($attachmentId)
    {
        return Attachment::where('id', $attachmentId)->firstOrFail();
    }

    /**
     * Обновить вложение.
     */
    public function updateAttachment($validated, $id)
    {
        $attachment = Attachment::findOrFail($id);
        $attachment->update($validated);

        // Обновляем файл, если передан
        if ($validated['file'] ?? false) {
            $file = $validated['file'];
            $filePath = $file->storeAs(config('app.attachment_path'), Str::uuid().'.'.$file->getClientOriginalExtension(), 'public');
            $attachment->update([
                'path_file' => $filePath,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return $attachment;
    }

    /**
     * Удалить вложение.
     */
    public function deleteAttachment($attachmentId)
    {
        $attachment = Attachment::where('id', $attachmentId)->firstOrFail();
        Storage::delete('public/'.$attachment->path_file);
        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted successfully.']);
    }

    /**
     * Скачать файл вложения по user_id.
     */
    public function downloadFileByUser($userId)
    {
        $client = Client::find($userId);

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404); // Клиент не найден (id)
        }

        // Проверяем, не истекла ли лицензия
        if ($client->licence_expired_at && Carbon::parse($client->licence_expired_at)->isPast()) {
            return response()->json(['error' => 'License expired'], 403);
        }

        $attachment = Attachment::where('user_id', $client->id)->first();
        if (!$attachment) {
            return response()->json(['error' => 'No attachments found for this user'], 404); // Неверный user_id
        }

        $filePath = storage_path('app/public/' . $attachment->path_file);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist on server'], 404); // Попытка получить несуществующий файл
        }

        return response()->download($filePath);
    }
}
