<?php

namespace App\Http\Resources\Attachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this -> id,
            'documentable_id'   => $this -> documentable_id,
            'documentable_type' => $this -> documentable_type,
            'path_file'         => $this -> path_file,
            'name'              => $this -> name,
            'user_id'           => $this -> user_id,
        ];
    
    }
}
