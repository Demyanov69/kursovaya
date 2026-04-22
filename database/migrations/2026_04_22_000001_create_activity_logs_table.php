<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event_type'); // login, logout, course_created, etc
            $table->string('ip_address')->nullable();

            $table->text('description')->nullable();

            // для фильтрации преподавателя (связь с курсами)
            $table->unsignedBigInteger('course_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();

            $table->index(['event_type']);
            $table->index(['user_id']);
            $table->index(['course_id']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};