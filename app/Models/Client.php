<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'id', 'name', 'description', 'inn', 'address',
        'licence_expired_at', 'is_deleted'
    ];

    // Указываем, что `id` будет UUID
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'is_deleted' => 'boolean',
        'licence_expired_at' => 'datetime',
    ];

    // Генерация UUID перед сохранением записи
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Связь с вложениями
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id', 'id');
    }
}

// id client 50d2660b-f63b-4288-bf7c-c56cc5e45840
// id 