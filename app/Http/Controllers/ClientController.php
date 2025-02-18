<?php
namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\Core\ResponseService;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\DeleteClientRequest;
use App\Http\Requests\Client\ShowClientRequest;

class ClientController extends Controller
{
    protected ClientService $clientService;
    protected ResponseService $responseService;

    public function __construct(ClientService $clientService, ResponseService $responseService)
    {
        $this->clientService = $clientService;
        $this->responseService = $responseService;
    }

    /**
     * Создание клиента
     */
    public function createClient(CreateClientRequest $request)
    {
        return $this->clientService->createClient($request->validated());
    }

    /**
     * Получение всех клиентов
     */
    public function indexClients()
    {
        return $this->clientService->indexClients();
    }

    /**
     * Получение клиента по ID
     */
    public function showClient(ShowClientRequest $request, string $client_id)
    {
        return $this->clientService->showClient($client_id);
    }

    /**
     * Обновление клиента
     */
    public function updateClient(UpdateClientRequest $request, string $client_id)
    {
        return $this->clientService->updateClient($client_id, $request->validated());
    }

    /**
     * Логическое удаление клиента
     */
    public function deleteClient(DeleteClientRequest $request, string $client_id)
    {
        return $this->clientService->deleteClient($client_id);
    }
}
