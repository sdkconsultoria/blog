<?php
namespace Sdkconsultoria\Blog\Helpers;

use App\Helpers\Pages;
use App\Helpers\Sections;
/**
 *
 */
class Menu
{
    public static function generate()
    {
        return [
            [
                'name' => __('base::attributes.section.items'),
                'icon' => 'list-alt',
                'url'  => 'images.index',
                'items' => Sections::generate()
            ],
            [
                'name' => __('base::app.pages'),
                'icon' => 'file-text',
                'items' => Pages::generate()
            ],
            [
                'name' => __('blog::blog.blogs'),
                'icon' => 'book',
                'visible' => auth()->user()->hasRole('admin'),
                'items' => [
                    [
                        'name' => __('blog::blog.blog_categories'),
                        'icon' => 'book',
                        'url'  => 'blog.index',
                        'crud' => 'blog',
                    ],
                    [
                        'name' => __('blog::blog.blog_posts'),
                        'icon' => 'book',
                        'url'  => 'blog-post.index',
                        'crud' => 'blog-post',
                    ],
                ]
            ]
        ];
    }
}
