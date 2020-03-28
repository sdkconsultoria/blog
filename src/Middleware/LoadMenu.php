<?php

namespace Sdkconsultoria\Blog\Middleware;

use Closure;
use Sdkconsultoria\Blog\Models\Blog;

class LoadMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $menu = Blog::parents()->get();
        $menu_categories = [];
        foreach ($menu as $key => $item) {
            $menu_categories[] = $this->generateMenu($item);
        }

        view()->share('menu_categories', $menu_categories);
        return $next($request);
    }

    protected function generateMenu($item)
    {
        $submenu = [
            'name' => $item->name,
            'url'  => ['blog-category', $item->seoname]
        ];

        $submenu['items'] = [];

        foreach ($item->childs as $key => $sub_item) {
            $submenu['items'][] = $this->generateMenu($sub_item);
        }

        $submenu['items'] = array_merge($submenu['items'], $this->generatePosts($item->posts));

        return $submenu;
    }

    protected function generatePosts($blogs)
    {
        $menu_blogs = [];

        foreach ($blogs as $key => $blog) {
            $menu_blogs[] = [
                'name' => $blog->name,
                'url'  => ['post', $blog->seoname]
            ];
        }

        return $menu_blogs;
    }

}
