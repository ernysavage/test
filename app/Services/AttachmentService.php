<?php
namespace App\Services;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttachmentService
{
    // Получить все вложения
    public function getAllAttachments()
    {
        return Attachment::all();
    }

    // Создать вложение
    public function createAttachment(array $data)
    {
        $client = Client::findOrFail($data['user_id']);
        if ($user_id->is_deleted){
            throw new \Exception('Невозможно создать запись для удаленного клиента.');
        }
    
        // Находим клиента по documentable_id
        $client = Client::findOrFail($data['documentable_id']);
        $data['documentable_type'] = Client::class;

        if (isset($data['file'])) {
            // Если file является массивом и содержит более одного элемента – выбрасываем ошибку
            if (is_array($data['file']) && count($data['file']) > 1) {
                throw new \Exception('Загружать можно только один файл.');
            }
            
            // Если file является массивом с единственным элементом, берём его; иначе – сам file
            $file = is_array($data['file']) ? $data['file'][0] : $data['file'];
            
            // Удаляем любые переданные значения для этих полей, чтобы гарантировать их автоматическое заполнение
            unset($data['file_name'], $data['check_sum'], $data['path_file']);
            
            // Сохраняем файл в директории "attachments" на локальном диске
            $path = $file->store('attachments', 'local');
            $data['path_file'] = $path;
            // Получаем оригинальное имя файла
            $data['file_name'] = $file->getClientOriginalName();
            
            // Вычисляем SHA256 хэш файла
            $filePath = Storage::disk('local')->path($path);
            $data['check_sum'] = hash_file('sha256', $filePath);
            
            // Удаляем временное поле с файлом
            unset($data['file']);
        }

        // Создаём запись в базе данных
        return Attachment::create($data);
    }

    // Обновить вложение
    public function updateAttachment(string $id, array $data)
    {

        if ($client->is_deleted){
            throw new \Exception('Невозможно создать запись для удаленного клиента.');
        }

        $attachment = Attachment::findOrFail($id);

        if (isset($data['file'])) {
            // Если загружен новый файл
            $file = $data['file'];
            $filePath = $file->store('attachments', 'local');
            $data['path_file'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['check_sum'] = hash_file('sha256', Storage::disk('local')->path($filePath));
        }

        $attachment->update($data);
        return $attachment;
    }

    // Удалить вложение
    public function deleteAttachment(string $id)
    {
        $attachment = Attachment::findOrFail($id);
        Storage::disk('local')->delete($attachment->path_file); // Удаляем файл из локального хранилища
        $attachment->delete();
        return true;
    }

    // Получить вложение по ID
    public function getAttachmentById(string $id)
    {
        return Attachment::findOrFail($id);
    }

    // Скачать файл для пользователя по ID
    public function downloadByUserId(string $user_id)
    {
        // Используем where для поиска клиента по UUID
        $client = Client::where('id', $user_id)->first(); 

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        if ($client->licence_expired_at && Carbon::parse($client->licence_expired_at)->isPast()) {
            return response()->json(['error' => 'License expired'], 403);
        }

        // Находим первое вложение для клиента
        $attachment = Attachment::where('documentable_id', $client->id)
            ->where('documentable_type', Client::class)
            ->first();

        if (!$attachment) {
            return response()->json(['error' => 'No attachments found for this user'], 404);
        }
        
        // Возвращаем файл
        return Storage::disk('local')->download($attachment->path_file, $attachment->file_name);
    }
}
