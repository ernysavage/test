<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;

class UpdateClientRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:128',
            'description' => 'nullable|string',
            'inn' => 'nullable|numeric',
            'address' => 'nullable|string',
            'licence_expired_at' => 'nullable|date',
            'is_deleted' => 'nullable|boolean',
        ];
    }
}
