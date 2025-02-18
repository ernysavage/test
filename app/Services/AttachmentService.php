<?php
namespace App\Services;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    /**
     * Получить все вложения
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexAttachments()
    {
        return Attachment::all();
    }

    /**
     * Создать новое вложение
     *
     * @param array $data
     * @return Attachment
     */
    public function createAttachment(array $data)
    {
        // Находим клиента по user_id
        $client = Client::findOrFail($data['user_id']);
        
        // Привязываем вложение к клиенту
        $data['documentable_type'] = Client::class;
        $data['documentable_id'] = $client->id;

        // Обработка файла (если он передан)
        if (isset($data['file'])) {
            $file = $data['file'];
            $path = $file->store('attachments', 'local');
            
            // Обновляем данные о файле
            $data['path_file'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['check_sum'] = hash_file('sha256', Storage::disk('local')->path($path));
        }

        // Создаем и сохраняем запись в базе данных
        return Attachment::create($data);
    }

    /**
     * Обновить существующее вложение
     *
     * @param string $id
     * @param array $data
     * @return Attachment
     */
    public function updateAttachment(string $id, array $data)
{
    $attachment = Attachment::find($id);

    if (!$attachment) {
        return null;
    }

    $updated = $attachment->update($data);

    return $attachment;
}

    public function deleteAttachment(string $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        // Удаляем файл из хранилища
        if (Storage::exists($attachment->path_file)) {
            Storage::delete($attachment->path_file);
        }

        // Удаляем запись из базы данных
        $attachment->delete();
    }

    /**
     * Получить вложение по ID
     *
     * @param string $id
     * @return Attachment
     */
    public function showAttachment(string $id)
    {
        return Attachment::findOrFail($id);
    }

    /**
     * Скачать вложение по ID пользователя
     *
     * @param string $user_id
     * @return \Illuminate\Http\Response
     */
    public function downloadByUserId(string $user_id)
{
    // Находим вложение по user_id
    $attachment = Attachment::where('documentable_id', $user_id)
                            ->where('documentable_type', 'App\Models\Client')
                            ->firstOrFail();

    // Получаем путь к файлу
    $path = $attachment->path_file;

    // Скачиваем файл с помощью Storage диска
    return Storage::disk('secure_files')->download($path);
}

    /**
     * Сохранить файл
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function storeFile($file)
    {
        return $file->store('attachments');
    }
}
