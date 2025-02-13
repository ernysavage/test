<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\DeleteClientRequest;
use App\Http\Requests\Client\GetClientByIdRequest;
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
        $clients = $this->clientService->listClients();
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
     * Получить информацию о клиенте по ID.
     * Здесь идентификатор извлекается внутри GetClientByIdRequest через prepareForValidation().
     */
    public function getClientById(GetClientByIdRequest $request)
    {
        $client = $this->clientService->getClientById($request);

        if (isset($client['error'])) {
            return response()->json($client, 400);
        }

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    /**
     * Обновить данные клиента.
     * Идентификатор клиента передаётся из URL (например, {clientId}) как второй параметр.
     */
    public function updateClient(UpdateClientRequest $request, $client_id)
    {
        $result = $this->clientService->updateClient($client_id, $request);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        if (!$result) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json($result);
    }

    
    public function deleteClient(DeleteClientRequest $request)
{
    $validated = $request->validated(); // Получаем данные из запроса
    return $this->clientService->deleteClient($validated['client_id']);
}

}
