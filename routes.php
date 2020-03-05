<?php

Route::prefix('admin')
->middleware(['web', 'role:super-admin'])
->namespace('\Sdkconsultoria\Blog\Controllers')
->group(function () {
    Route::resource('blog', 'BlogController')->middleware('auth');
    Route::resource('blog-post', 'BlogPostController');

    Route::get('blog-post/create/{blog}', 'BlogPostController@create')->name('blog-post.create-blog');
    Route::get('page/{page}', 'BlogPostController@page')->name('blog-post.page');
    Route::put('page/{page}', 'BlogPostController@page')->name('blog-post.page');

    Route::get('pages/{page}', 'BlogController@pages')->name('blog-post.pages');
    Route::get('pages/blog/{blog}', 'BlogPostController@blogs')->name('blog-post.blogs');
    Route::post('pages/{page}', 'BlogController@pages')->name('blog-post.pages');
    Route::delete('page/{page}', 'BlogController@deletePages')->name('blog-post.pages.destroy');

    Route::post('blog/addKey/{id}', 'BlogController@addKey')->name('blog.addkey');
    Route::post('blog/removeKey/{id}', 'BlogController@removeKey')->name('blog.removekey');

    Route::post('blog/addSize/{id}', 'BlogController@addSize')->name('blog.addsize');
    Route::post('blog/removeSize/{id}', 'BlogController@removeSize')->name('blog.removesize');

    Route::post('blog/addImage/{id}', 'BlogController@addImage')->name('blog.addimage');
    Route::post('blog/removeImage/{id}', 'BlogController@removeImage')->name('blog.removeimage');

    Route::post('blog-post/addImage/{id}', 'BlogPostController@saveImage')->name('blog-post.saveimage');
    Route::post('blog-post/removeImage/{id}', 'BlogPostController@removeImage')->name('blog-post.removeimage');

    Route::post('blog-post/images/save-sizes', 'BlogPostController@saveImageSizes')->name('blog-post.image.save-size');

});
