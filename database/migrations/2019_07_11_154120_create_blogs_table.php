<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Sdkconsultoria\Blog\Models\Blog;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->commonFields();

            $table->unsignedBigInteger('blog_id')->unsigned()->index()->nullable();
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('restrict');

            $table->unsignedBigInteger('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('blogs')->onDelete('restrict');

            $table->string('identifier', 64)->nullable()->unique();
            $table->string('name', 64)->nullable();
            $table->string('seoname', 64)->nullable();
            $table->string('title', 120)->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('images_types')->nullable();
            $table->text('sizes')->nullable();
            $table->text('keys')->nullable();
            $table->boolean('is_popular')->default(0);
            $table->boolean('is_featured')->default(0);
        });

        $blog              = new Blog();
        $blog->name        = 'configs';
        $blog->created_by  = 1;
        $blog->description = 'Usado para generar posts de configuración';
        $blog->status      = 0;
        $blog->save();

        $blog               = new Blog();
        $blog->name         = 'pages';
        $blog->created_by   = 1;
        $blog->description  = 'Usado para generar páginas comunes';
        $blog->status       = 0;
        $blog->images_types = serialize(config('base.images_types'));
        $blog->sizes        = serialize(config('base.images'));
        $blog->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
