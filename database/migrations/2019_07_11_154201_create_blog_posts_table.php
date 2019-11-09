<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');

            $table->unsignedBigInteger('blogs_id')->unsigned()->index();
            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('restrict');

            $table->unsignedBigInteger('blog_posts_id')->unsigned()->index()->nullable();
            $table->foreign('blog_posts_id')->references('id')->on('blog_posts')->onDelete('restrict');

            $table->smallInteger('status')->default('10');
            $table->string('name', 64)->nullable();
            $table->string('seoname', 64)->nullable();
            $table->string('language', 10)->nullable();
            $table->string('title', 120)->nullable();
            $table->text('description')->nullable();
            $table->string('meta_author', 120)->nullable();
            $table->string('meta_description', 120)->nullable();
            $table->string('meta_keywords', 120)->nullable();
            $table->text('images_types')->nullable();
            $table->text('sizes')->nullable();
            $table->text('keys')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
