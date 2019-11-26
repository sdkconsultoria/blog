<?php

$prefix = 'Sdkconsultoria\Blog\Controllers';

Route::group(['middleware' => ['web']], function () use ($prefix){
    Route::resource('admin/blog', $prefix.'\BlogController')->middleware('auth');

    Route::resource('admin/blog-post', $prefix.'\BlogPostController')->middleware('auth');
    Route::get('/admin/blog-post/create/{blog}', $prefix.'\BlogPostController@create')->name('blog-post.create-blog');
    Route::get('/admin/page/{page}', $prefix.'\BlogPostController@page')->name('blog-post.page');
    Route::put('/admin/page/{page}', $prefix.'\BlogPostController@page')->name('blog-post.page');

    Route::get('/admin/pages/{page}', $prefix.'\BlogController@pages')->name('blog-post.pages');
    Route::post('/admin/pages/{page}', $prefix.'\BlogController@pages')->name('blog-post.pages');
    Route::delete('/admin/page/{page}', $prefix.'\BlogController@deletePages')->name('blog-post.pages.destroy');

    Route::post('/admin/blog/addKey/{id}', $prefix.'\BlogController@addKey')->name('blog.addkey');
    Route::post('/admin/blog/removeKey/{id}', $prefix.'\BlogController@removeKey')->name('blog.removekey');

    Route::post('/admin/blog/addSize/{id}', $prefix.'\BlogController@addSize')->name('blog.addsize');
    Route::post('/admin/blog/removeSize/{id}', $prefix.'\BlogController@removeSize')->name('blog.removesize');

    Route::post('/admin/blog/addImage/{id}', $prefix.'\BlogController@addImage')->name('blog.addimage');
    Route::post('/admin/blog/removeImage/{id}', $prefix.'\BlogController@removeImage')->name('blog.removeimage');

    Route::post('/admin/blog-post/addImage/{id}', $prefix.'\BlogPostController@saveImage')->name('blog-post.saveimage');
    Route::post('/admin/blog-post/removeImage/{id}', $prefix.'\BlogPostController@removeImage')->name('blog-post.removeimage');

    Route::post('/admin/blog-post/images/save-sizes', $prefix.'\BlogPostController@saveImageSizes')->name('blog-post.image.save-size');
});
