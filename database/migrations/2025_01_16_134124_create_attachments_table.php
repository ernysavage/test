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
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'))->comment('Идентификатор записи');
            $table->string('documentable_type')->comment('Тип документа (таблица)'); // Для типа связи
            $table->uuid('documentable_id')->comment('Ссылка на документ');    // Для UUID связи
            $table->string('name')->nullable(false)->comment('Наименование документа');
            $table->string('number_document')->nullable()->comment('Номер документа');
            $table->string('register_number')->nullable()->comment('Регистрационный номер');
            $table->date('date_register')->nullable()->comment('Дата регистрации');
            $table->date('date_document')->nullable()->comment('Дата документа');
            $table->text('list_item')->nullable()->comment('Пункт перечня');
            $table->text('path_file')->nullable()->comment('Полный путь до файла');
            $table->text('check_sum')->nullable()->comment('Контрольная сумма'); 
            $table->uuid('user_id')->comment('Ссылка на пользователя');
            $table->string('file_name')->nullable()->comment('Имя файла');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
