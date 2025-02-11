<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;

class DeleteClientRequest extends BaseRequest
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
     * Правила валидации для удаления клиента.
     */
    public function rules()
    {
        return [
            'client_id' => 'required|uuid|exists:clients,id',
        ];
    }
}
