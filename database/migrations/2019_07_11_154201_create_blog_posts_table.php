<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Sdkconsultoria\Blog\Models\BlogPost;

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
            $table->commonFields();

            $table->unsignedBigInteger('blog_id')->unsigned()->index()->nullable();
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('restrict');

            $table->unsignedBigInteger('blog_post_id')->unsigned()->index()->nullable();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('restrict');

            $table->unsignedBigInteger('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('blog_posts')->onDelete('restrict');

            $table->string('identifier', 200)->unique()->nullable();
            $table->string('name', 200)->nullable();
            $table->string('seoname', 200)->nullable();
            $table->string('language', 10)->nullable();
            $table->string('title', 120)->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('meta_author', 120)->nullable();
            $table->string('meta_description', 120)->nullable();
            $table->string('meta_keywords', 120)->nullable();
            $table->text('images_types')->nullable();
            $table->text('sizes')->nullable();
            $table->text('keys')->nullable();
            $table->date('published_at')->nullable();
            $table->bigInteger('visits')->default(1);
            $table->integer('stars')->default(0);
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_featured')->default(0);
        });

        $blog = new BlogPost();
        $blog->name = 'generic';
        $blog->created_by = 1;
        $blog->blog_id = 1;
        $blog->description = 'Usado para configuraciones genericas';
        $blog->status = 0;
        $blog->save();
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
