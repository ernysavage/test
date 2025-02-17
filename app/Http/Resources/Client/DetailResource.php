<?php
namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'description'       => $this->description,
            'inn'               => $this->inn,
            'address'           => $this->address,
            'licence_expired_at'=> $this->licence_expired_at,
            'is_deleted'        => $this->is_deleted,
        ];
    }
}
