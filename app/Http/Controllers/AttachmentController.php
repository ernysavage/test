<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attachment\CreateAttachmentRequest;
use App\Http\Requests\Attachment\UpdateAttachmentRequest;
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

    public function getAllAttachments(): JsonResponse
    {
        $attachments = $this->attachmentService->getAllAttachments();
        return response()->json($attachments);
    }

    public function createAttachment(CreateAttachmentRequest $request): JsonResponse
    {
        $attachment = $this->attachmentService->createAttachment($request->validated());
        return response()->json($attachment, 201);
    }

    public function updateAttachment(UpdateAttachmentRequest $request, string $id): JsonResponse
{
    $attachment = $this->attachmentService->updateAttachment($id, $request->validated());
    return response()->json($attachment);
}


    public function deleteAttachment(string $id): JsonResponse
    {
        $this->attachmentService->deleteAttachment($id);
        return response()->json(null, 204);
    }

    public function getAttachmentById(string $id): JsonResponse
    {
        $attachment = $this->attachmentService->getAttachmentById($id);
        return response()->json($attachment);
    }

    public function downloadByUserID(Request $request, string $userId)
    {
        return $this->attachmentService->downloadByUserID($userId);
    }
}