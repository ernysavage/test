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
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'))->comment('ID идентификатор клиента');
            $table->string('name', 128)->comment('Имя клиента');  
            $table->text('description')->nullable()->comment('Описание клиента'); 
            $table->bigInteger('inn')->nullable()->comment('ИНН');
            $table->text('address')->nullable()->comment('Адрес');
            $table->timestamp('licence_expired_at')->nullable()->comment('Дата истечения лицензии');
            $table->boolean('is_deleted')->default(false)->comment('Флаг удаления');
            $table->timestamps();  
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');  // Удаляем таблицу clients
    }
}
