<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Отображает список всех клиентов.
     */
    public function index()
    {
        $clients = $this->clientService->getAllClients();
        return response()->json($clients);
    }

    /**
     * Создает нового клиента.
     */
    public function store(Request $request)
    {
        $result = $this->clientService->createClient($request->all());

        if (isset($result['errors'])) {
            return response()->json(['errors' => $result['errors']], 400);
        }

        return response()->json([
            'message' => 'Client created successfully.',
            'client' => $result
        ], 201);
    }

    /**
     * Отображает конкретного клиента по ID.
     */
    public function show($clientUuid)
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
     * Обновляет данные клиента по UUID.
     */
    public function update(Request $request, $clientUuid)
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
     * Удаляет клиента по UUID.
     */
    public function destroy($clientUuid)
    {
        $result = $this->clientService->deleteClient($clientUuid);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json(['message' => 'Client deleted successfully'], 204);
    }
}
