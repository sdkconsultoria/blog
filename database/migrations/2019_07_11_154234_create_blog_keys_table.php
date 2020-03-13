<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->commonFields();

            $table->unsignedBigInteger('blog_post_id')->unsigned()->index()->nullable();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('restrict');

            $table->string('category', 64)->nullable();
            $table->string('seocategory', 64)->nullable();

            $table->string('name', 64);
            $table->string('seoname', 64);
            $table->string('value')->nullable();
            $table->string('seovalue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_keys');
    }
}
