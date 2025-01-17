<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Request $request)
    {
        // Получаем файл по ID
        $attachment = Attachment::find($request->file_id);

        // Проверяем, существует ли файл
        if (!$attachment) {
            return response()->json([
                'error' => 'File not found',
                'code' => 'file_not_found',
            ], 404);
        }

        // Проверяем, истекла ли лицензия клиента для этого файла
        if ($attachment->isLicenseExpired()) {
            return response()->json([
                'error' => 'License expired',
                'code' => 'license_expired',
            ], 403); // Код ошибки 403, доступ запрещен
        }

        // Если лицензия не истекла, возвращаем файл
        return response()->download(storage_path('app/' . $attachment->path_file), $attachment->file_name);
    }
}
