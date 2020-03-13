<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->commonFields();

            $table->unsignedBigInteger('blog_post_id')->unsigned()->index();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('restrict');

            $table->string('type', 30)->nullable();
            $table->string('extension', 6);
            $table->string('name')->nullable();
            $table->string('alt', 124)->nullable();
            $table->text('sizes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_images');
    }
}
