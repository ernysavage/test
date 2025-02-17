<?php
namespace App\Services\Core;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    public function success($data = null, string $message = 'Запрос выполнен успешно.', int $status_code = 200, $total = null)
    {
        // Если total не передан, вычисляем его автоматически:
        if (is_null($total)) {
            if (is_array($data) || $data instanceof \Countable) {
                $total = count($data);
            } elseif (!is_null($data)) {
                $total = 1;
            } else {
                $total = 0;
            }
        }
        
        return response()->json([
            'message'     => $message,
            'status'      => true,
            'status_code' => $status_code,
            'data'        => [
                'data'  => $data,
                'total' => $total,
            ]
        ], $status_code);
    }

    public function error(string $message, int $status_code = 400)
    {
        return response()->json([
            'message'     => $message,
            'status'      => false,
            'status_code' => $status_code,
            'data'        => null
        ], $status_code);
    }
}
