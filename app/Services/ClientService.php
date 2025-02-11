<?php

namespace App\Services;

use App\Models\Client;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\Client\DeleteClientRequest;
use App\Http\Requests\Client\GetClientByIDRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientService
{
    public function getAllClients()
    {
        return Client::all();
    }

   
    public function createClient(CreateClientRequest $request)
    {
        $validated = $request->validated();

        $client = new Client();
        $client->fill($validated);
        $client->save();

        return $client;
    }

   
    public function getClientById(GetClientByIDRequest $request)
    {
        $validated = $request->validated();
        $client = Client::where('id', $validated['client_id'])->first();

        if (!$client) {
            return ['error' => 'Client not found'];
        }

        return $client;
    }

   
    public function updateClient($clientId, UpdateClientRequest $request)
    {
        $client = Client::where('id', $clientId)->first();

        if (!$client) {
            return ['error' => 'Client not found'];
        }

        $validated = $request->validated();

        // Если нет изменений, возвращаем старые данные
        if (empty($validated)) {
            return $client;
        }

        $client->update(array_filter($validated));

        return $client;
    }

    
        public function deleteClient(DeleteClientRequest $request)
    {
        $validated = $request->validated();
         $client = Client::find($validated['client_id']);

        if (!$client) {
            return ['error' => 'Client not found'];
        }

        $client->delete(); // Мягкое удаление

        return ['message' => 'Client marked as deleted successfully'];
    }
}
