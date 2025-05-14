<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('title');
            $table->string('lang', 20)->nullable();
            $table->foreignId('translation_origin_model_id')
                ->nullable()
                ->constrained('pages')
                ->cascadeOnDelete();
            $table->string('slug', 255);
            $table->longText('content');
            $table->longText('searchable_content')->nullable();
            $table->string('status');
            $table->foreignId('parent_page_id')
                ->nullable()
                ->constrained('pages')
                ->nullOnDelete();

            // SEO
            $table->boolean('disable_indexation')->default(0);
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('opengraph_title')->nullable();
            $table->longText('opengraph_description')->nullable();
            $table->unsignedBigInteger('opengraph_picture')->nullable();
            $table->foreign('opengraph_picture')
                ->references('id')
                ->on('media');
            $table->longText('opengraph_picture_alt')->nullable();

            $table->dateTime('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['lang', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
