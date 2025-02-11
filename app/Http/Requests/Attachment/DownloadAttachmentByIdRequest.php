<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;

class DownloadAttachmentByIdRequest extends BaseRequest
{
    
    protected function prepareForValidation()
    {
        $attachmentId = $this->route('user_id');

        $this->merge([
            'user_id' => $attachmentId ?? null,
        ]);
    }

   
    public function rules()
    {
        return [
            'user_id' => 'required|uuid|exists:clients,id',
        ];
    }
}
