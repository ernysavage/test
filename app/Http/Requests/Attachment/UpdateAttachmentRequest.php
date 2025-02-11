<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;


class UpdateAttachmentRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        // Получаем attachment_id из маршрута и передаем его в данные запроса
        $attachmentId = $this->route('attachment_id');

        $this->merge([
            'attachment_id' => $attachmentId ?? null,
        ]);
    }


    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:clients,id',
            'attachment_id' => 'required|uuid|exists:attachments,id',
            'file' => 'required|file|max:10240',
            'name' => 'nullable|string|max:255',
            'number_document' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'date_register' => 'nullable|date',
            'date_document' => 'nullable|date',
            'list_item' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Файл обязателен.',
            
        ];
    }
  
}
