<?php
namespace App\Services;

use App\Models\Client;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\DeleteClientRequest;
use App\Http\Requests\Client\GetClientByIDRequest;
use App\Http\Resources\Client\DetailResource;
use App\Http\Resources\Client\ListResource;
use App\Http\Resources\Support\ResponseResource;
use Illuminate\Http\Response;

class ClientService
{
    /**
     * Создание клиента
     */
    public function createClient(CreateClientRequest $request): ResponseResource
    {
        // Валидация данных через запрос
        $validated = $request->validated();

        // Создание клиента
        $client = Client::create($validated);

        // Возврат ответа через ресурс
        return new ResponseResource(new DetailResource($client), 'Клиент успешно создан', true, Response::HTTP_CREATED, 'clients');
    }

    /**
     * Получение клиента по ID
     */
    public function getClientById(string $clientId): ResponseResource
    {
        // Поиск клиента по ID
        $client = Client::find($clientId);

        // Если клиент не найден
        if (!$client) {
            return new ResponseResource(null, 'Клиент не найден', false, Response::HTTP_NOT_FOUND, 'clients');
        }

        // Возврат ответа через ресурс
        return new ResponseResource(new DetailResource($client), 'Клиент найден', true, Response::HTTP_OK, 'clients');
    }

    /**
     * Обновление клиента
     */
    public function updateClient(UpdateClientRequest $request, string $clientId): ResponseResource
    {
        // Поиск клиента по ID
        $client = Client::find($clientId);

        // Если клиент не найден
        if (!$client) {
            return new ResponseResource(null, 'Клиент не найден', false, Response::HTTP_NOT_FOUND, 'clients');
        }

        // Валидация данных и обновление клиента
        $validated = $request->validated();
        $client->update($validated);

        // Возврат ответа через ресурс
        return new ResponseResource(new DetailResource($client), 'Клиент успешно обновлен', true, Response::HTTP_OK, 'clients');
    }

    /**
     * Удаление клиента
     */
    public function deleteClient(string $clientId): ResponseResource
    {
        // Поиск клиента по ID
        $client = Client::find($clientId);

        Client::where('id', '=', $clientId)->update(['is_deleted' => true]);

        // Если клиент не найден
        if (!$client) {
            return new ResponseResource(null, 'Клиент не найден', false, Response::HTTP_NOT_FOUND, 'clients');
        }

        // Помечаем клиента как удаленного
        $client->is_deleted = true;
        $client->save();

        // Возврат ответа через ресурс
        return new ResponseResource(new DetailResource($client), 'Клиент успешно удален', true, Response::HTTP_OK, 'clients');
    }

    /**
     * Получение всех клиентов
     */
    public function getAllClients(): ResponseResource
    {
        // Получаем всех клиентов (только не удаленные)
        $clients = Client::where('is_deleted', false)->get();

        // Возвращаем коллекцию клиентов через ресурс
        return new ResponseResource(ListResource::collection($clients), 'Список клиентов', true, Response::HTTP_OK, 'clients');
    }
}
