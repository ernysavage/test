<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;


class UpdateAttachmentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
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
