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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable(); // лекционный материал (текст/встраиваемая презентация)
            $table->string('assignment_file')->nullable(); // путь к файлу задания
            $table->dateTime('available_from')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->integer('late_penalty_percent')->default(0); // % штрафа
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
