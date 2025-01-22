<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Guid\Guid; // Используем для валидации UUID

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

        return response()->json([
            'message' => 'Client created successfully.',
            'client' => $client
        ], 201);
    }

    /**
     * Отображает конкретного клиента по ID.
     */
    public function show($clientUuid)
    {
        // Проверяем, что UUID валиден
        if (!Guid::isValid($clientUuid)) {
            return response()->json(['error' => 'Invalid UUID format'], 400);
        }

        $client = Client::where('id', $clientUuid)->first();

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
    // Проверяем, что UUID валиден
    if (!Guid::isValid($clientUuid)) {
        return response()->json(['error' => 'Invalid UUID format'], 400);
    }

    // Ищем клиента по UUID
    $client = Client::where('id', $clientUuid)->first();

    // Если клиент не найден, возвращаем ошибку
    if (!$client) {
        return response()->json(['error' => 'Client not found'], 404);
    }

    // Валидация данных запроса (включает все поля, которые могут быть обновлены)
    $validated = $request->validate([
        'name' => 'nullable|string|max:128',
        'description' => 'nullable|string',
        'inn' => 'nullable|numeric',
        'address' => 'nullable|string',
        'licence_expired_at' => 'nullable|date',
        'is_deleted' => 'nullable|boolean',
    ]);

    // Обновляем только те поля, которые были переданы в запросе
    $client->update(array_filter($validated)); // array_filter удаляет пустые значения

    // Возвращаем обновленного клиента
    return response()->json($client);
}


    /**
     * Удаляет клиента по UUID.
     */
    public function destroy($clientUuid)
    {
        // Проверяем, что UUID валиден
        if (!Guid::isValid($clientUuid)) {
            return response()->json(['error' => 'Invalid UUID format'], 400);
        }

        $client = Client::where('id', $clientUuid)->first();

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted successfully'], 204);
    }
}
