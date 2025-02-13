<?php
namespace App\Http\Resources\Support;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    protected ?string $message;
    protected bool $status;
    protected int $statusCode;

    public function __construct($resource, ?string $message = null, bool $status = true, int $statusCode = 200)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
        $this->statusCode = $statusCode;
    }

    public function toArray($request)
    {
        return [
            'message'     => $this->message,
            'status'      => $this->status,
            'status_code' => $this->statusCode,
            'client'      => $this->resource, // Теперь `client` без лишней вложенности
        ];
    }
}
