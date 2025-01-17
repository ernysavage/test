<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateClientsTable extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            // UUID для id с авто-генерацией
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            // Остальные поля
            $table->string('name', 128);  // Название клиента (макс. 128 символов)
            $table->text('description')->nullable();  // Описание клиента
            $table->bigInteger('inn')->nullable();  // ИНН
            $table->text('address')->nullable();  // Адрес клиента
            $table->timestamp('licence_expired_at')->nullable();  // Дата истечения лицензии
            $table->boolean('is_deleted')->default(false);  // Флаг удаленности

            // Тimestamps для created_at и updated_at
            $table->timestamps();  // created_at и updated_at
        });
    }

    /**
     * Откат миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');  // Удаляем таблицу clients
    }
}
