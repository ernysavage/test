<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;

class ShowClientRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'client_id' => 'required|uuid|exists:clients,id',
        ];
    }
}
