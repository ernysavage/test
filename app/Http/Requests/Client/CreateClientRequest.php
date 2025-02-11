<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;


class CreateClientRequest extends BaseRequest
{
  
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:128',
            'description' => 'required|string',
            'inn' => 'required|numeric',
            'address' => 'required|string',
            'licence_expired_at' => 'required|date',
            'is_deleted' => 'required|boolean',
            
        ];
    }
}
