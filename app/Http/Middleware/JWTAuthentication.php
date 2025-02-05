<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttachmentService
{
    // Получаем название диска из файла .env (по умолчанию "public")
    private string $disk;

    // Конструктор – инициализирует переменную диска
    public function __construct()
    {
        $this->disk = env('D_FOLDER', 'ATTACHMENTS_STORAGE_PATH');
    }

    // Возвращает все записи вложений из базы данных
    public function getAllAttachments()
    {
        return Attachment::all();
    }

    // Создаёт новое вложение, загружая файл, если он передан
    public function createAttachment(array $data)
    {
        // Находим клиента по documentable_id, если клиент не найден, выбрасывается исключение
        $client = $this->getClientById($data['documentable_id']);
        // Указываем тип модели клиента
        $data['documentable_type'] = Client::class;

        // Если файл не передан, выбрасываем исключение
        if (!isset($data['file'])) {
            throw new \Exception('Файл обязателен для загрузки.');
        }

        // Если файл передан в виде массива с более чем одним элементом, выбрасываем исключение
        if (is_array($data['file']) && count($data['file']) > 1) {
            throw new \Exception('Загружать можно только один файл.');
        }

        // Если файл передан как массив с единственным элементом, выбираем его, иначе используем напрямую
        $file = is_array($data['file']) ? $data['file'][0] : $data['file'];

        // Удаляем поля, которые будут заполнены автоматически
        unset($data['file_name'], $data['check_sum'], $data['path_file']);

        // Сохраняем файл и обновляем массив данных (в $data появятся информация о пути, оригинальное имя и хэш)
        $this->storeFile($file, $data);

        // Создаём запись вложения в базе и возвращаем её
        return Attachment::create($data);
    }

    // Метод для скачивания файла по идентификатору пользователя (клиента)
    public function downloadByUserId($userId)
    {
        try {
            // Находим клиента по ID, если клиент не найден – исключение
            $client = $this->getClientById($userId);
        } catch (ModelNotFoundException $e) {
            // Если клиент не найден, возвращаем JSON с ошибкой и статусом 404
            return response()->json(['error' => 'Client not found'], 404);
        }

        // Если срок действия лицензии клиента истёк, возвращаем JSON с ошибкой и статусом 403
        if ($client->licence_expired_at && Carbon::parse($client->licence_expired_at)->isPast()) {
            return response()->json(['error' => 'License expired'], 403);
        }

        try {
            // Получаем вложение, связанное с клиентом
            $attachment = $this->getAttachmentByClient($client);
        } catch (\Exception $e) {
            // Если вложение не найдено, возвращаем JSON с ошибкой и статусом 404
            return response()->json(['error' => 'No attachments found for this user'], 404);
        }
        
        // Возвращаем скачивание файла с указанного диска, используя путь и оригинальное имя файла
        return Storage::disk($this->disk)->download($attachment->path_file, $attachment->file_name);
    }

    // Приватный метод для сохранения файла и заполнения данных о нём
    private function storeFile($file, array &$data)
    {
        // Получаем название папки для вложений из .env (по умолчанию "attachments")
        $folder = env('D_ATTACHMENTS_FOLDER', 'attachments');

        // Сохраняем файл в указанной папке на заданном диске, возвращается путь к файлу
        $path = $file->store($folder, $this->disk);
        $data['path_file'] = $path;

        // Сохраняем оригинальное имя файла
        $data['file_name'] = $file->getClientOriginalName();

        // Вычисляем SHA-256 хэш файла для проверки целостности
        $filePath = Storage::disk($this->disk)->path($path);
        $data['check_sum'] = hash_file('sha256', $filePath);

        // Удаляем временное поле, содержащее объект файла, так как оно больше не нужно
        unset($data['file']);
    }

    // Приватный метод для получения клиента по ID, выбрасывает исключение, если клиент не найден
    private function getClientById($id)
    {
        return Client::findOrFail($id);
    }

    // Приватный метод для получения вложения, связанного с клиентом, выбрасывает исключение, если вложение не найдено
    private function getAttachmentByClient(Client $client)
    {
        $attachment = Attachment::where('documentable_id', $client->id)
            ->where('documentable_type', Client::class)
            ->first();

        if (!$attachment) {
            throw new \Exception('No attachments found for this user');
        }

        return $attachment;
    }
}
