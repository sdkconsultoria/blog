<?php
namespace Sdkconsultoria\Blog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadTranslationsFrom(__DIR__.'/../translations', 'blog');
        $this->loadViewsFrom(__DIR__.'/../views', 'blog');
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->make('Sdkconsultoria\Blog\controllers\BlogController');
    }
}
