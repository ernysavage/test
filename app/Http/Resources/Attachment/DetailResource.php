<?php

namespace App\Http\Resources\Attachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'              => $this->name,
            'number_document'   => $this->number_document, 
            'register_number'   => $this->register_number,
            'date_register'     => $this->date_register,
            'date_document'     => $this->date_document,
            'path_file'         => $this->path_file,
            'file_name'         => $this->file_name,
            'user_id'           => $this->user_id,
            'documentable_id'   => $this->documentable_id,
            'documentable_type' => $this->documentable_type,
            'check_sum'         => $this->check_sum,
            'list_item'         => $this->list_item,

        ];
    }
}
