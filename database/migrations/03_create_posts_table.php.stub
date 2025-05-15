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
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 255);
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->foreign('thumbnail_id')
                ->references('id')
                ->on('media');
            $table->longText('thumbnail_alt')->nullable();
            $table->string('lang', 20)->nullable();
            $table->foreignId('translation_origin_model_id')
                ->nullable()
                ->constrained('posts')
                ->cascadeOnDelete();
            $table->string('status');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->longText('searchable_content')->nullable();
            $table->boolean('is_top_article')->default(false);

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
            $table->timestamps();

            $table->unique(['lang', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
