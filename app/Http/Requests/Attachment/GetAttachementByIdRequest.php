<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;

class GetAttachmentByIdRequest extends BaseRequest
{
    /**
     * Подготовка данных для валидации.
     */
    protected function prepareForValidation()
    {
        $attachmentId = $this->route('attachment_id');

        $this->merge([
            'attachment_id' => $attachmentId ?? null,
        ]);
    }

    /**
     * Правила валидации.
     */
    public function rules()
    {
        return [
            'attachment_id' => 'required|uuid|exists:clients,id',
        ];
    }
}
