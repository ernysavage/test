<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attachment extends Model
{
    // Указываем имя таблицы, если оно отличается от стандартного
    protected $table = 'attachments';

    // Указываем первичный ключ, если он отличается от стандартного
    protected $primaryKey = 'id';

    // Указываем, что первичный ключ не является автоинкрементным
    public $incrementing = false;

    // Указываем тип данных первичного ключа
    protected $keyType = 'string';

    // Указываем, что модель использует временные метки
    public $timestamps = true;

    // Указываем формат временных меток
    protected $dateFormat = 'Y-m-d H:i:s';

    // Указываем атрибуты, которые могут быть массово присвоены
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

    // Указываем атрибуты, которые должны быть скрыты при преобразовании модели в массив или JSON
    protected $hidden = [
        'check_sum',
    ];

    // Указываем атрибуты, которые должны быть кастомизированы
    protected $casts = [
        'date_register' => 'datetime',
        'date_document' => 'datetime',
    ];

    // Событие перед сохранением
    protected static function boot()
    {
        parent::boot();

        // Событие "creating" для автоматического заполнения полей
        static::creating(function ($attachment) {
            // Генерация уникального documentable_id
            $attachment->documentable_id = Str::uuid();
            // Автоматическое заполнение даты документа
            $attachment->date_document = now();
        });
    }

    // Переопределяем метод toArray, чтобы контролировать, какие атрибуты возвращаются
    public function toArray()
    {
        $attributes = parent::toArray();

        // Добавляем новый атрибут 'full_name', объединяя 'name' и 'file_name'
        $attributes['full_name'] = $this->name . ' ' . $this->file_name;

        return $attributes;
    }
    public function client()
{
    return $this->belongsTo(Client::class, 'user_id');
}
}
