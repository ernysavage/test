<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

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

    return response()->json($client, 201);
}


    /**
     * Отображает конкретного клиента по ID.
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Обновляет данные клиента по ID.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:128',
            'description' => 'nullable|string',
            'inn' => 'required|numeric',
            'address' => 'required|string',
            'licence_expired_at' => 'nullable|date',
            'is_deleted' => 'boolean',
        ]);

        $client->update($validated);
        return response()->json($client);
    }

    /**
     * Удаляет клиента по ID.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(null, 204);
    }
}
