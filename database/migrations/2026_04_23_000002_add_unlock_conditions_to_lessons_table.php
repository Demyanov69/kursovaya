<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('required_lesson_id')->nullable()->after('module_id');
            $table->integer('required_min_score')->nullable()->after('required_lesson_id');

            $table->foreign('required_lesson_id')
                ->references('id')->on('lessons')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['required_lesson_id']);
            $table->dropColumn(['required_lesson_id', 'required_min_score']);
        });
    }
};