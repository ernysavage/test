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
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
        
            // Полиморфная связь с типом UUID для documentable_id
            $table->string('documentable_type'); // Для типа связи
            $table->uuid('documentable_id');    // Для UUID связи
        
            // Дополнительные поля
            $table->string('name')->nullable(false);
            $table->string('number_document')->nullable();
            $table->string('register_number')->nullable();
            $table->date('date_register')->nullable();
            $table->date('date_document')->nullable();
            $table->text('list_item')->nullable();
            $table->text('path_file')->nullable();
            $table->text('check_sum')->nullable();  
            $table->uuid('user_id');
        
            // Имя файла
            $table->string('file_name')->nullable();
        
            // Время создания и обновления записи
            $table->timestamps();
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
