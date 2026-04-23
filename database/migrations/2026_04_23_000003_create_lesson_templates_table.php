<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonTemplatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_templates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('teacher_id');
            $table->string('name');
            $table->longText('blocks_json');

            $table->timestamps();

            $table->foreign('teacher_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_templates');
    }
}