<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAttachmentsTable extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            // UUID для id с авто-генерацией
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'))->comment('Идентификатор записи');

            // Ссылка на документ и тип документа
            $table->uuid('documentable_id')->nullable(false)->comment('Ссылка на документ');
            $table->string('documentable_type')->nullable()->comment('Тип документа (таблица)');
            
            // Дополнительные поля для хранения информации о документе
            $table->string('name')->nullable(false)->comment('Наименование документа');
            $table->string('number_document')->nullable()->comment('Номер документа');
            $table->string('register_number')->nullable()->comment('Регистрационный номер');
            $table->date('date_register')->nullable()->comment('Дата регистрации');
            $table->date('date_document')->nullable()->comment('Дата документа');
            $table->text('list_item')->nullable()->comment('Пункт перечня');
            $table->text('path_file')->nullable()->comment('Полный путь до файла');
            $table->text('check_sum')->nullable()->comment('Контрольная сумма');
            
            // Внешний ключ на таблицу clients
            $table->uuid('user_id')->nullable()->constrained('clients')->onDelete('set null')->comment('Ссылка на клиента');
            
            // Имя файла
            $table->string('file_name')->nullable()->comment('Имя файла');
            
            // Время создания и обновления записи
            $table->timestamps(); // Добавляем поля created_at и updated_at
        });
    }

    /**
     * Откат миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
