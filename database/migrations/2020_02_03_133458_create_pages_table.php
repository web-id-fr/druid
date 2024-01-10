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
            $table->string('lang')->nullable();
            $table->longText('slug');
            $table->longText('content');
            $table->longText('searchable_content')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('parent_page_id')->nullable();
            $table->foreign('parent_page_id')->references('id')->on('pages')->onDelete('set null');

            // SEO
            $table->boolean('indexation')->default(0);
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('opengraph_title')->nullable();
            $table->longText('opengraph_description')->nullable();
            $table->string('opengraph_picture')->nullable();
            $table->longText('opengraph_picture_alt')->nullable();

            $table->dateTime('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
