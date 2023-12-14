<?php

use App\Models\User;
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
            $table->string('slug');
            $table->string('post_image')->nullable();
            $table->string('status');
            $table->text('extrait')->nullable();
            $table->longText('content');
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

            $table->integer('order')->nullable();

            $table->dateTime('publish_at')->nullable();
            $table->timestamps();
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
