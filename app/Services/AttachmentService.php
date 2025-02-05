<?php
namespace App\Services;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttachmentService
{
    public function getAllAttachments()
    {
        return Attachment::all();
    }

    public function createAttachment(array $data)
{
    // Находим клиента по documentable_id
    $client = Client::findOrFail($data['documentable_id']);
    $data['documentable_type'] = Client::class;

    if (isset($data['file'])) {
        // Если file является массивом и содержит более одного элемента – выбрасываем ошибку
        if (is_array($data['file']) && count($data['file']) > 1) {
            // Можно выбросить исключение или вернуть ошибку
            throw new \Exception('Загружать можно только один файл.');
        }
        
        // Если file является массивом с единственным элементом, берём его; иначе — сам file
        $file = is_array($data['file']) ? $data['file'][0] : $data['file'];
        
        // Удаляем любые переданные значения для этих полей,
        // чтобы гарантировать их автоматическое заполнение
        unset($data['file_name'], $data['check_sum'], $data['path_file']);
        
        // Сохраняем файл в директории "attachments" на публичном диске
        $path = $file->store('attachments', 'public');
        $data['path_file'] = $path;
        // Получаем оригинальное имя файла
        $data['file_name'] = $file->getClientOriginalName();
        
        // Вычисляем SHA-256 хэш файла
        $filePath = \Storage::disk('public')->path($path);
        $data['check_sum'] = hash_file('sha256', $filePath);
        
        // Удаляем временное поле с файлом
        unset($data['file']);
    }

    
    return Attachment::create($data);
}

    public function downloadByUserId($userId)
    {
        $client = Client::find($userId);

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        if ($client->licence_expired_at && Carbon::parse($client->licence_expired_at)->isPast()) {
            return response()->json(['error' => 'License expired'], 403);
        }

        $attachment = Attachment::where('documentable_id', $client->id)
            ->where('documentable_type', Client::class)
            ->first();

        if (!$attachment) {
            return response()->json(['error' => 'No attachments found for this user'], 404);
        }
        
        return Storage::disk('public')->download($attachment->path_file, $attachment->file_name);
    }
}
