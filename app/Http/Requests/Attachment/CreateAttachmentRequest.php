<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;

class CreateAttachmentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'documentable_id' => 'required|uuid|exists:clients,id', // ID клиента должно быть валидным UUID и существовать в таблице clients
            'documentable_type' => 'required|string|in:App\Models\Client', // тип документа должен быть Client
            'file' => 'required|file',
            'name' => 'nullable|string|max:255',
            'user_id' => 'required|uuid|exists:clients,id',
            'number_document' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'date_register' => 'nullable|date',
            'date_document' => 'nullable|date',
            'list_item' => 'nullable|string',
            'path_file' => 'nullable|string',
            'check_sum' => 'nullable|string',
            'file_name' => 'nullable|string|max:255',
         // ID пользователя должно быть валидным UUID и существовать в таблице clients
        ];
    }

    public function messages(): array
    {
        return [
            'documentable_id.required' => 'ID документа обязателен.',
            'documentable_id.exists' => 'Документ с таким ID не найден.',
            'documentable_type.required' => 'Тип документа обязателен.',
            'documentable_type.in' => 'Недопустимый тип документа.',
            'file.required' => 'Файл обязателен.',
            'user_id.required' => 'ID пользователя обязателен.',
            'user_id.exists' => 'Пользователь с таким ID не найден.',
        ];
    }
}
