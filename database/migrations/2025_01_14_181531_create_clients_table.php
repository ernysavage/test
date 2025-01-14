<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('clients', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('name', 128);
        $table->text('description')->nullable();
        $table->decimal('inn', 12, 0)->unique(); // NUMERIC(12, 0)
        $table->text('address')->nullable();
        $table->timestamp('licence_expired_at')->nullable();
        $table->boolean('is_deleted')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
