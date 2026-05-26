<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glossary_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('glossary_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->tinyInteger('rating');

            $table->timestamps();

            $table->unique(['glossary_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glossary_ratings');
    }
};