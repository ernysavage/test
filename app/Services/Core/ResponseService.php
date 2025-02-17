<?php

namespace App\Services\Core;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ResponseService
{
    const OK = Response::HTTP_OK;
    const MESSAGES = 'messages';

    /**
     * Возвращает ответ в формате JSON.
     * @param array|string $data Данные для ответа
     * @return JsonResponse
     */
    public static function responseToJson($data): JsonResponse
    {
        return response()->json($data, $data['status_code'] ?? self::OK);
    }

    /**
     * Форматирует коллекцию данных в ответ.
     *
     * @param Collection|array $data Данные
     * @param string|null $message Сообщение
     * @param bool $status Статус выполнения запроса
     * @param int $statusCode Код ответа
     * @return JsonResponse Ответ в формате JSON
     */
    public static function formatCollectionResponse($data, ?string $message = null, bool $status = true, int $statusCode = self::OK): JsonResponse
    {
        // Получаем количество элементов в коллекции (или массиве)
        $total = ($data instanceof Collection || is_array($data)) ? count($data) : 0;

        // Формируем массив данных для ответа
        $formattedData = [
            'message' => $message ?? 'Запрос выполнен успешно.',
            'status' => $status,
            'status_code' => $statusCode,
            'data' => $data,  // Здесь уже нет лишней обертки
            'total' => $total,
        ];

        return response()->json($formattedData, $statusCode);
    }

    /**
     * Форматирует одиночный объект в ответ.
     *
     * @param mixed $data Данные
     * @param string|null $message Сообщение
     * @param bool $status Статус выполнения запроса
     * @param int $statusCode Код ответа
     * @return JsonResponse Ответ в формате JSON
     */
    public static function formatSingleResponse($data, ?string $message = null, bool $status = true, int $statusCode = self::OK): JsonResponse
    {
        $formattedData = [
            'message' => $message ?? 'Запрос выполнен успешно.',
            'status' => $status,
            'status_code' => $statusCode,
            'data' => $data,  // Здесь также нет лишней обертки
            'total' => 1,
        ];

        return response()->json($formattedData, $statusCode);
    }
}
