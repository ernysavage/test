<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID пользователя');
            $table->string('name')->comment('Имя пользователя');
            $table->string('email')->unique()->comment('Email');
            $table->timestamp('email_verified_at')->nullable()->comment('Подтверждение Email');
            $table->string('password')->comment('Пароль');
            $table->rememberToken()->comment('Токен');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
