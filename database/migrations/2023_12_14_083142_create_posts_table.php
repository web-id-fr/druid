<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 255);
            $table->string('post_image')->nullable();
            $table->string('lang', 20)->nullable();
            $table->string('status');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->longText('searchable_content')->nullable();
            $table->boolean('is_top_article')->default(false);

            $table->boolean('indexation')->default(false);
            $table->boolean('follow')->default(false);
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('opengraph_title')->nullable();
            $table->longText('opengraph_description')->nullable();
            $table->string('opengraph_picture')->nullable();
            $table->longText('post_image_alt')->nullable();
            $table->longText('opengraph_picture_alt')->nullable();

            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->unique(['lang', 'slug'],);
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
