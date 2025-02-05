<?php

namespace App\Services;

use App\Models\Client;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Ramsey\Uuid\Guid\Guid;

class ClientService
{
    public function getAllClients()
    {
        return Client::all();
    }

    public function createClient(CreateClientRequest $request)
    {
        // Валидация входных данных через Request
        $validated = $request->validated();

        // Создание нового клиента
        $client = new Client();
        $client->fill($validated);
        $client->save();

        return $client;
    }

    public function getClientByUuid($clientUuid)
    {
        if (!Guid::isValid($clientUuid)) {
            return ['error' => 'Invalid UUID format'];
        }

        return Client::where('id', $clientUuid)->first();
    }

    public function updateClient($clientUuid, UpdateClientRequest $request)
    {
        if (!Guid::isValid($clientUuid)) {
            return ['error' => 'Invalid UUID format'];
        }

        $client = Client::where('id', $clientUuid)->first();

        if (!$client) {
            return ['error' => 'Client not found'];
        }

        // Валидация обновленных данных через Request
        $validated = $request->validated();

        $client->update(array_filter($validated));

        return $client;
    }

    public function deleteClient($clientUuid)
    {
        if (!Guid::isValid($clientUuid)) {
            return ['error' => 'Invalid UUID format'];
        }

        $client = Client::where('id', $clientUuid)->first();

        if (!$client) {
            return ['error' => 'Client not found'];
        }

        // Мягкое удаление
        $client->is_deleted = true; // Устанавливаем флаг soft delete
        $client->save();

        return ['message' => 'Client marked as deleted successfully'];
    }
}
