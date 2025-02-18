<?php
namespace App\Services;

use App\Models\Client;
use App\Services\Core\ResponseService;

class ClientService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    // Метод для создания клиента
    public function createClient(array $validatedData)
    {
        // Создание нового клиента
        $client = Client::create($validatedData);

        // Ответ с успешным созданием клиента
        return $this->responseService->success($client, 'Запись успешно создана.');
    }

    // Метод для получения всех клиентов
    public function indexClients()
    {
        // Получаем всех клиентов
        $clients = Client::all();

        // Ответ с успешным получением данных (total вычислится автоматически)
        return $this->responseService->success($clients, 'Данные успешно получены.');
    }

    // Метод для получения клиента по ID
    public function showClient(string $client_id)
    {
        // Ищем клиента по ID
        $client = Client::find($client_id);

        if (!$client) {
            return $this->responseService->error('Клиент не найден.', 404);
        }

        // Ответ с данными клиента (total будет 1)
        return $this->responseService->success($client, 'Данные успешно получены.');
    }

    // Метод для обновления данных клиента
    public function updateClient(string $client_id, array $validatedData)
    {
        // Ищем клиента по ID
        $client = Client::find($client_id);

        if (!$client) {
            return $this->responseService->error('Клиент не найден.', 404);
        }

        // Обновляем данные клиента
        $client->update($validatedData);

        // Ответ с успешным обновлением (total будет 1)
        return $this->responseService->success($client, 'Запись успешно обновлена.');
    }

    // Метод для удаления клиента (фактическое удаление через флаг is_deleted)
    public function deleteClient(string $client_id)
{
    // Ищем клиента по ID
    $client = Client::find($client_id);

    if (!$client) {
        return $this->responseService->error('Клиент не найден.', 404);
    }

    // Обновляем флаг is_deleted на true (не удаляем физически)
    $client->update(['is_deleted' => true]);

    // Возвращаем успешный ответ с данными клиента, который имеет флаг is_deleted = true
    return $this->responseService->success($client, 'Запись успешно удалена.');
}
}
