<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь для выполнения запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Подготовка данных перед валидацией.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'client_id' => $this->route('client_id'),
        ]);
    }

    /**
     * Локализованные названия атрибутов (для сообщений об ошибках).
     */
    public function attributes(): array
    {
        return [
            'client_id' => 'Client ID',
        ];
    }

    /**
     * Кастомные сообщения валидации.
     */
    public function messages(): array
    {
        return [
            'confirmed' => 'Поле ":attribute" не подтверждено.',
            'required'  => 'Поле ":attribute" является обязательным.',
        ];
    }

    /**
     * Обработчик ошибок валидации.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
