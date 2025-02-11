<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;

class GetClientByIdRequest extends BaseRequest
{
    /**
     * Подготавливаем данные для валидации, извлекая clientId из маршрута.
     */
    protected function prepareForValidation()
    {
        // Получаем параметр clientId из маршрута (убедитесь, что в маршрутах он называется именно "clientId")
        $clientId = $this->route('client_id');

        $this->merge([
            'client_id' => $clientId ?? null,
        ]);
    }

    /**
     * Правила валидации для получения клиента.
     */
    public function rules()
    {
        return [
            'client_id' => 'required|uuid|exists:clients,id',
        ];
    }
}
