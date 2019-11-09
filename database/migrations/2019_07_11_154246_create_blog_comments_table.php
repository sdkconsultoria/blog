<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');

            $table->unsignedBigInteger('blog_posts_id')->unsigned()->index()->nullable();
            $table->foreign('blog_posts_id')->references('id')->on('blog_posts')->onDelete('restrict');

            $table->unsignedBigInteger('blog_comments_id')->unsigned()->index()->nullable();
            $table->foreign('blog_comments_id')->references('id')->on('blog_comments')->onDelete('restrict');

            $table->text('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
}
