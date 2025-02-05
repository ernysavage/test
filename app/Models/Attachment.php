<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'attachments';

    // Указываем, что ID не автоинкрементируется
    public $incrementing = false;

    // Указываем, что тип ID - строка (UUID)
    protected $keyType = 'string';

    // Задаем поля, которые могут быть массово присвоены
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

    // Указываем, что documentable_id является строкой (UUID)
    protected $casts = [
        'documentable_id' => 'string',  // Преобразуем в строку (UUID)
    ];

    // Определяем полиморфную связь
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attachment) {
            if ($attachment->uploaded_file) {
                $file = $attachment->uploaded_file;

                // Сохранение файла
                $path = $file->store('attachments');
                $attachment->path_file = $path;

                // Оригинальное имя файла
                $attachment->file_name = $file->getClientOriginalName();

                // Контрольная сумма
                $attachment->check_sum = md5_file(storage_path('app/' . $path));

                // Если не хотите сохранять поле uploaded_file в БД
                unset($attachment->uploaded_file);
            }
        });
    }
    
}
