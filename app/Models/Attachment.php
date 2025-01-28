<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attachment extends Model
{
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'id',
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

    protected $hidden = [
        'check_sum',
    ];

    protected $casts = [
        'date_register' => 'datetime',
        'date_document' => 'datetime',
    ];

    // Событие перед сохранением
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attachment) {
            // Генерация уникального documentable_id, если оно еще не установлено
            if (empty($attachment->documentable_id)) {
                $attachment->documentable_id = Str::uuid();
            }
            // Устанавливаем дату документа, если она еще не установлена
            if (empty($attachment->date_document)) {
                $attachment->date_document = now();
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'user_id');
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        $attributes['full_name'] = $this->name . ' ' . $this->file_name;

        return $attributes;
    }
}
