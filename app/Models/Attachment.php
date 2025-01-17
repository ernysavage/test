<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    // Указываем, что это не инкрементируемый ID, а UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Разрешаем массовое назначение этих полей
    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'name',
        'number_document',
        'register_number',
        'date_register',
        'date_document',
        'list_item',
        'path_file',
        'check_sum',
        'user_id',
        'file_name',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(Client::class, 'user_id');
    }

    // Полиморфная связь с другими моделями (например, Client)
    public function documentable()
    {
        return $this->morphTo();
    }
}
