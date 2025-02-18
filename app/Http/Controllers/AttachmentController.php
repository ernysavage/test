<?php
namespace App\Http\Controllers;

use App\Http\Requests\Attachment\CreateAttachmentRequest;
use App\Http\Requests\Attachment\UpdateAttachmentRequest;
use App\Http\Requests\Attachment\DeleteAttachmentRequest;
use App\Http\Requests\Attachment\ShowAttachmentRequest;
use App\Http\Requests\Attachment\ListAttachmentRequest;
use App\Http\Requests\Attachment\DownloadAttachmentRequest;
use App\Services\AttachmentService;
use App\Services\Core\ResponseService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Attachment\ListResource;
use App\Http\Resources\Attachment\DetailResource;

class AttachmentController extends Controller
{
    protected $attachmentService;
    protected $responseService;

    public function __construct(AttachmentService $attachmentService, ResponseService $responseService)
    {
        $this->attachmentService = $attachmentService;
        $this->responseService = $responseService;
    }

    // Получить все вложения
    public function indexAttachment(ListAttachmentRequest $request): JsonResponse
    {
        // Валидация данных
        $validatedData = $request->validated();  

        // Получаем данные от сервиса
        $attachments = $this->attachmentService->indexAttachments($validatedData);

        // Возвращаем ответ через ResponseService
        return $this->responseService->success(ListResource::collection($attachments));
    }

    // Создать вложение
    public function createAttachment(CreateAttachmentRequest $request): JsonResponse
{
    // Валидация данных
    $validatedData = $request->validated();

    try {
        // Передаем данные в сервис
        $attachment = $this->attachmentService->createAttachment($validatedData);

        // Возвращаем успешный ответ
        return $this->responseService->success(new DetailResource($attachment), 'Вложение успешно создано.', 201);
    } catch (\Exception $e) {
        // В случае ошибки возвращаем сообщение
        return $this->responseService->error($e->getMessage(), 400);
    }
}

    // Обновить вложение
    public function updateAttachment(UpdateAttachmentRequest $request, string $id): JsonResponse
    {
        // Валидация данных
        $validatedData = $request->validated();  

        // Получаем данные от сервиса
        $attachment = $this->attachmentService->updateAttachment($id, $validatedData);

        // Возвращаем ответ через ResponseService
        return $this->responseService->success(new DetailResource($attachment), 'Вложение успешно обновлено.');
    }

    // Удалить вложение
    public function deleteAttachment(DeleteAttachmentRequest $request, string $id): JsonResponse
    {
        // Валидация данных
        $validatedData = $request->validated();  

        // Удаляем вложение через сервис
        $this->attachmentService->deleteAttachment($id);

        // Возвращаем пустой ответ с кодом 204
        return $this->responseService->success(null, 'Вложение успешно удалено.', 204);
    }

    // Получить вложение по ID
    public function showAttachment(ShowAttachmentRequest $request, int $id): JsonResponse
    {
        // Валидация данных
        $validatedData = $request->validated();  

        // Получаем данные от сервиса
        $attachment = $this->attachmentService->showAttachment($id);

        // Возвращаем ответ через ResponseService
        return $this->responseService->success(new DetailResource($attachment));
    }

    // Скачать вложение по ID пользователя
    public function downloadAttachment(DownloadAttachmentRequest $request, string $user_id)
{
    // Валидация данных уже выполнена
    return $this->attachmentService->downloadByUserId($user_id);
}


}
