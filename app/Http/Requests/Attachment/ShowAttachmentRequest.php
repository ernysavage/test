<?php

namespace App\Http\Requests\Attachment;

use Illuminate\Foundation\Http\BaseRequest;

class ShowAttachmentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attachment_id' => 'required|uuid|exists:clients,id',
        ];
    }
}
