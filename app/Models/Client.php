<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;  // Подключаем Str
use Illuminate\Support\Facades\Log;


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
                $model->id = (string) Str::uuid();  // Генерация UUID
                \Log::info('Generated UUID for client: ' . $model->id); // Логирование UUID
            }
        });

    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id', 'id');
    }
}
