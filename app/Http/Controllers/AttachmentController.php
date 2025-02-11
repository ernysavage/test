<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attachment\CreateAttachmentRequest;
use App\Http\Requests\Attachment\UpdateAttachmentRequest;
use App\Http\Requests\Attachment\DeleteAttachmentRequest;
use App\Http\Requests\Attachment\DownloadAttachmentByIdRequest;
use App\Http\Requests\Attachment\GetAttachmentByIdRequest;
use App\Services\AttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    // Получить все вложения
    public function getAllAttachments(): JsonResponse
    {
        $attachments = $this->attachmentService->getAllAttachments();
        return response()->json($attachments);
    }

    // Создать вложение
    public function createAttachment(CreateAttachmentRequest $request): JsonResponse
    {
        
        $attachment = $this->attachmentService->createAttachment($request->validated());
        return response()->json($attachment, 201);
    }

    // Обновить вложение
    public function updateAttachment(UpdateAttachmentRequest $request, string $id): JsonResponse
    {
        // Валидация данных из запроса уже выполнена в UpdateAttachmentRequest
        $attachment = $this->attachmentService->updateAttachment($id, $request->validated());
        return response()->json($attachment);
    }

    // Удалить вложение
    public function deleteAttachment(string $id): JsonResponse
    {
        // Валидация attachment_id уже выполнена в DeleteAttachmentRequest
        $this->attachmentService->deleteAttachment($id);
        return response()->json(null, 204); // Возвращаем пустой ответ с кодом 204
    }

    // Получить вложение по ID
    public function getAttachmentById(string $id): JsonResponse
    {
        // Валидация attachment_id уже выполнена в GetAttachmentByIdRequest
        $attachment = $this->attachmentService->getAttachmentById($id);
        return response()->json($attachment);
    }

    // Скачать вложение по ID пользователя
    public function downloadByUserID(DownloadAttachmentByIdRequest $request, string $user_id)
    {
        // Валидация данных из запроса уже выполнена в DownloadAttachmentRequest
        return $this->attachmentService->downloadByUserId($user_id);
    }
}