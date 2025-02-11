<?php
namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;

class UpdateClientRequest extends BaseRequest
{
    /**
     * Подготавливаем данные для валидации, извлекая clientId из маршрута.
     */
    protected function prepareForValidation()
    {
        // Получаем параметр clientId из маршрута
        $clientId = $this->route('client_id');

        $this->merge([
            'client_id' => $clientId ?? null,
        ]);
    }

    /**
     * Правила валидации для обновления клиента.
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|uuid|exists:clients,id', // id теперь соответствует полю в базе данных
            'name' => 'nullable|string|max:128',
            'description' => 'nullable|string',
            'inn' => 'nullable|numeric',
            'address' => 'nullable|string',
            'licence_expired_at' => 'nullable|date',
            'is_deleted' => 'nullable|boolean',
        ];
    }
}
