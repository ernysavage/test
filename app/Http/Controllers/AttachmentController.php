<?php 

namespace App\Http\Controllers;

use App\Services\AttachmentService;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Получить все вложения.
     */
    public function getAllAttachments()
    {
        return response()->json($this->attachmentService->getAllAttachments());
    }

    /**
     * Создать новое вложение.
     */
    public function createAttachment(Request $request)
    {
        $attachment = $this->attachmentService->createAttachment($request);
        return response()->json([
            'message' => 'Attachment created successfully.',
            'attachment' => $attachment
        ], 201);
    }

    /**
     * Получить вложение по ID.
     */
    public function getAttachmentById($attachmentId)
    {
        $attachment = $this->attachmentService->getAttachmentById($attachmentId);
        return response()->json($attachment);
    }

    /**
     * Обновить вложение.
     */
    public function updateAttachment(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'date_document' => 'nullable|date',
            'file' => 'nullable|file|mimes:jpeg,png,pdf,docx',
            'user_id' => 'nullable|exists:clients,id',
        ]);

        $attachment = $this->attachmentService->updateAttachment($validated, $id);

        return response()->json($attachment, 200);
    }

    /**
     * Удалить вложение.
     */
    public function deleteAttachment($attachmentId)
    {
        $this->attachmentService->deleteAttachment($attachmentId);
        return response()->json(['message' => 'Attachment deleted successfully'], 204);
    }

    /**
     * Скачать файл вложения по user_id.
     */
    public function downloadFileByUser($userId)
    {
        return $this->attachmentService->downloadFileByUser($userId);
    }
}
