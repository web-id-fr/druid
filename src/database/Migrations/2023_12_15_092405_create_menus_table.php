<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('lang', 20)->nullable();
            $table->foreignId('translation_origin_model_id')
                ->nullable()
                ->constrained('menus')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['lang', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
