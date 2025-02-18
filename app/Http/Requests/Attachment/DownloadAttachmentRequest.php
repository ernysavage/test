<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;
use App\Models\Client;

class DownloadAttachmentRequest extends BaseRequest
{
    public function rules()
{
    return [
        'user_id' => 'required|uuid|exists:clients,id',  // Валидация на существование пользователя
    ];
}


    public function messages(): array
    {
        return [
            'user_id.exists' => 'Пользователь с таким ID не найден в базе данных.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Извлекаем user_id из параметров маршрута
            $userId = $this->route('user_id');
            $client = Client::find($userId);

            // Проверяем срок действия лицензии и существование в бд
            if ($client && $client->licence_expired_at && $client->licence_expired_at < now()) {
                $validator->errors()->add('licence', 'Лицензия пользователя истекла.');
            } 
        });
    }
}
