<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\Core\ResponseService;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\DeleteClientRequest;
use App\Http\Requests\Client\GetClientByIDRequest;

class ClientController extends Controller
{
    protected ClientService $clientService;
    protected ResponseService $responseService;

    public function __construct(ClientService $clientService, ResponseService $responseService)
    {
        $this->clientService = $clientService;
        $this->responseService = $responseService;
    }

    // Если ClientService уже возвращает ResponseResource с нужной структурой,
    // то можно просто вернуть результат из сервиса:

    public function createClient(CreateClientRequest $request)
    {
        return $this->clientService->createClient($request);
    }

    public function listClients()
    {
        return $this->clientService->getAllClients();
    }

    public function getClientById(GetClientByIDRequest $request, string $client_id)
    {
        return $this->clientService->getClientById($client_id);
    }

    public function updateClient(UpdateClientRequest $request, string $client_id)
    {
        return $this->clientService->updateClient($request, $client_id);
    }

    public function deleteClient(DeleteClientRequest $request, string $client_id)
    {
        return $this->clientService->deleteClient($client_id);
    }
}
