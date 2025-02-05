<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Получить список всех клиентов.
     */
    public function listClients()
    {
        $clients = $this->clientService->getAllClients();
        return response()->json($clients);
    }

    /**
     * Создать нового клиента.
     */
    public function createClient(CreateClientRequest $request)
    {
        $result = $this->clientService->createClient($request);

        if (isset($result['errors'])) {
            return response()->json(['errors' => $result['errors']], 400);
        }

        return response()->json([
            'message' => 'Client created successfully.',
            'client'  => $result
        ], 201);
    }

    /**
     * Получить информацию о клиенте по UUID.
     */
    public function getClient($clientUuid)
    {
        $client = $this->clientService->getClientByUuid($clientUuid);

        if (isset($client['error'])) {
            return response()->json($client, 400);
        }

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    /**
     * Обновить данные клиента по UUID.
     */
    public function updateClient(UpdateClientRequest $request, $clientUuid)
    {
        $result = $this->clientService->updateClient($clientUuid, $request);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        if (!$result) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json($result);
    }

    /**
     * Мягко удалить клиента по UUID.
     */
    public function deleteClient($clientUuid)
    {
        $result = $this->clientService->deleteClient($clientUuid);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        // При успешном удалении можно вернуть статус 204 (No Content)
        return response()->json(['message' => 'Client marked as deleted successfully'], 204);
    }

}
