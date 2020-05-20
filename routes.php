<?php

Route::prefix('admin')
->middleware(['web', 'auth', 'role:super-admin', 'admin'])
->namespace('\Sdkconsultoria\Blog\Controllers')
->group(function () {
    Route::resource('blog', 'BlogController');
    Route::resource('blog-post', 'BlogPostController');

    Route::get('blog-post/create/{blog}', 'BlogPostController@create')->name('blog-post.create-blog');

    // Route::get('page/{page}', 'BlogPostController@page')->name('blog-post.page');
    // Route::put('page/{page}', 'BlogPostController@page')->name('blog-post.page');
    Route::match(['get', 'put'], 'page/{page}', 'BlogPostController@page')->name('blog-post.page');

    // Route::get('pages/{page}', 'BlogController@pages')->name('blog-post.pages');
    // Route::post('pages/{page}', 'BlogController@pages')->name('blog-post.pages');
    Route::match(['get', 'post'], 'pages/{page}', 'BlogController@pages')->name('blog-post.pages');

    Route::get('pages/blog/{blog}', 'BlogPostController@blogs')->name('blog-post.blogs');
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

    // Route::get('/search', 'BlogController@search')->name('search-post');

});

Route::middleware(['web', 'menu'])
->namespace('\App\Http\Controllers\Front')
->group(function () {
    Route::get('/search', 'BlogController@search')->name('search-post');
});

//
// Route::namespace('\App\Http\Controllers\Front')
// ->middleware(['menu'])
// ->group(function () {
//     Route::get('/categories', 'BlogController@categories')->name('blog-categories');
//     Route::get('/category/{seoname}', 'BlogController@category')->name('blog-category');
//     Route::get('/post/{seoname}', 'BlogController@post')->name('post');
// });
