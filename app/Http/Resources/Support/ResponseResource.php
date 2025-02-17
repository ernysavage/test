<?php
namespace App\Http\Resources\Support;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResponseResource extends JsonResource
{
    public function toArray($request)
{
    $resource = $this->resource;  // Получаем ресурс
    $total = $resource->count();
    // Если это коллекция или ResourceCollection
    if ($resource instanceof Collection || $resource instanceof ResourceCollection) {
        // Подсчитываем количество элементов в коллекции
        

        // Возвращаем данные с total
        return [
            'message' => $this->message,
            'status' => $this->status,
            'status_code' => $this->statusCode,
            'data' => [
                'data' => $resource,  // Данные коллекции
                'total' => $total,     // Общее количество элементов
            ],
        ];
    } else {
        // Если это одиночный объект, всегда total = 1
        $total = 1;

        // Возвращаем данные для одиночного объекта
        return [
            'message' => $this->message,
            'status' => $this->status,
            'status_code' => $this->statusCode,
            'data' => [
                'data' => [$this->resource],  // Одиночный объект
                'total' => $total,            // Всегда 1 для одиночного объекта
            ],
        ];
    }
}
}
