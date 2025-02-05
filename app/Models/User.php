<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Возвращаем ID пользователя как идентификатор JWT
    }

    public function getJWTCustomClaims()
    {
        return []; // Здесь могут быть дополнительные данные в JWT
    }

    public function setPasswordAttribute($password) {

        $this->attributes['password'] = Hash::make($password);
    }
}
