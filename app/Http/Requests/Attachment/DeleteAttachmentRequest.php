<?php
namespace App\Http\Requests\Attachment;

use App\Http\Requests\BaseRequest;

class DeleteAttachmentRequest extends BaseRequest
{
    
    public function rules()
    {
        return [
            'attachment_id' => 'required|uuid|exists:attachments,id',
        ];
    }
}
