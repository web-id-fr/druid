<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->unsignedInteger('order')->nullable();
            $table
                ->foreignId('parent_item_id')
                ->nullable()
                ->constrained('menu_items')
                ->nullOnDelete();
            $table->string('label')->nullable();
            $table->string('type');
            $table->string('custom_url')->nullable();
            $table->nullableMorphs('model');
            $table->string('target');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
