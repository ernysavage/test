<?php

namespace App\Services;

use App\Models\Client;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\DeleteClientRequest;
use App\Http\Requests\Client\GetClientByIDRequest;
use App\Http\Resources\Client\ListResource;
use App\Http\Resources\Client\DetailResource;
use App\Http\Resources\Support\ResponseResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class ClientService
{
    public function createClient(CreateClientRequest $request)
    {
        $validated = $request->validated();
        $client = Client::create($validated);
    
        return new ResponseResource(new DetailResource($client), 'Client created successfully');
    }
    
    public function getClientById(GetClientByIDRequest $request)
    {
        $validated = $request->validated();
        $client = Client::find($validated['client_id']);
    
        if (!$client) {
            return new ResponseResource(null, 'Client not found', false, Response::HTTP_NOT_FOUND);
        }
    
        return new ResponseResource(new DetailResource($client));
    }
    
    public function updateClient($clientId, UpdateClientRequest $request)
    {
        $client = Client::find($clientId);
    
        if (!$client) {
            return new ResponseResource(null, 'Client not found', false, Response::HTTP_NOT_FOUND);
        }
    
        $validated = $request->validated();
        if (!empty($validated)) {
            $client->update($validated);
        }
    
        return new ResponseResource(new DetailResource($client), 'Client updated successfully');
    }
    
    public function deleteClient(string $clientId): ResponseResource
    {
        $client = Client::where('id', $clientId)->firstOrFail();
        $client->is_deleted = true;
        $client->save();
    
        return new ResponseResource(new DetailResource($client), 'Клиент успешно удалён.');
    }

    // Добавляем метод getAllClients
    public function getAllClients()
    {
        $clients = Client::all();  // Получаем всех клиентов
        return new ResponseResource(ListResource::collection($clients));  // Возвращаем коллекцию клиентов в нужном формате
    }
}

