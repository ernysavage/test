<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Отображает список всех клиентов.
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    /**
     * Создает нового клиента.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:128',
            'description' => 'required|string',
            'inn' => 'required|numeric',
            'address' => 'required|string',
            'licence_expired_at' => 'required|date',
            'is_deleted' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $client = new Client();
        $client->name = $request->name;
        $client->description = $request->description;
        $client->inn = $request->inn;
        $client->address = $request->address;
        $client->licence_expired_at = $request->licence_expired_at;
        $client->is_deleted = $request->is_deleted;
        $client->save();

        // Формируем кастомный ответ
        return response()->json([
            'message' => 'Client created successfully.',
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'description' => $client->description,
                'inn' => $client->inn,
                'address' => $client->address,
                'licence_expired_at' => $client->licence_expired_at,
                'is_deleted' => $client->is_deleted,
                'created_at' => $client->created_at,
                'updated_at' => $client->updated_at,
            ]
        ], 201);
    }

    /**
     * Отображает конкретного клиента по ID.
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Обновляет данные клиента по UUID.
     */
    public function update(Request $request, $clientUuid)
    {
        // Ищем клиента по UUID
        $client = Client::where('id', $clientUuid)->first();

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        // Валидируем входные данные
        $validated = $request->validate([
            'name' => 'required|string|max:128',
            'description' => 'nullable|string',
            'inn' => 'required|numeric',
            'address' => 'required|string',
            'licence_expired_at' => 'nullable|date',
            'is_deleted' => 'boolean',
        ]);

        // Обновляем данные клиента
        $client->update($validated);
        return response()->json($client);
    }

    /**
     * Удаляет клиента по UUID.
     */
    public function destroy($clientUuid)
    {
        // Ищем клиента по UUID
        $client = Client::where('id', $clientUuid)->first();

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        // Удаляем клиента
        $client->delete();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Client deleted successfully'], 204);
    }
}
