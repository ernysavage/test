<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Guid\Guid;

class ClientService
{
    public function getAllClients()
    {
        return Client::all();
    }

    public function createClient($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:128',
            'description' => 'required|string',
            'inn' => 'required|numeric',
            'address' => 'required|string',
            'licence_expired_at' => 'required|date',
            'is_deleted' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $client = new Client();
        $client->fill($data);
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

    public function updateClient($clientUuid, $data)
    {
        if (!Guid::isValid($clientUuid)) {
            return ['error' => 'Invalid UUID format'];
        }

        $client = Client::where('id', $clientUuid)->first();

        if (!$client) {
            return ['error' => 'Client not found'];
        }

        $validated = $data->validate([
            'name' => 'nullable|string|max:128',
            'description' => 'nullable|string',
            'inn' => 'nullable|numeric',
            'address' => 'nullable|string',
            'licence_expired_at' => 'nullable|date',
            'is_deleted' => 'nullable|boolean',
        ]);

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

        $client->delete();

        return ['message' => 'Client deleted successfully'];
    }
}
