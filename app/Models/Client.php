<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
