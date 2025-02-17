<?php
namespace App\Http\Resources\Support;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class ResponseResource extends JsonResource
{
    public $message;
    public $status;
    public $statusCode;
    public $modelName;

    public function __construct($resource, ?string $message = null, bool $status = true, int $statusCode = 200, ?string $modelName = 'data')
    {
        if ($resource instanceof ResourceCollection || $resource instanceof Collection) {
            parent::__construct($resource);
        } else {
            parent::__construct(collect([$resource]));
        }

        $this->message = $message;
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->modelName = $modelName;
    }

    public function toArray($request)

    {
        $resource = $this->resource;

        if ($resource instanceof Collection || $resource instanceof ResourceCollection) {
            return [
                'message' => $this->message,
                'status' => $this->status,
                'status_code' => $this->statusCode,
                'data' => [
                    $this->modelName => $resource,
                    'total' => $resource->count(), // Подсчитываем количество для коллекции
                ],
            ];
        } else {
            return [
                'message' => $this->message,
                'status' => $this->status,
                'status_code' => $this->statusCode,
                'data' => [
                    $this->modelName => $resource,
                    'total' => 1, // Если это одиночный объект
                ],
            ];
        }
}
}
