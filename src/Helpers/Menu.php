<?php
namespace Sdkconsultoria\Blog\Helpers;

/**
 *
 */
class Menu
{
    public static function generate()
    {
        return [
            [
                'name' => __('base::app.pages'),
                'icon' => 'file-text',
                'items' => [
                    [
                        'name' => __('app.pages.about'),
                        'icon' => 'file-text',
                        'url'  => ['blog-post.page', 'about'],
                    ],
                    [
                        'name' => __('app.pages.faq'),
                        'icon' => 'file-text',
                        'url'  => ['blog-post.pages', 'faq'],
                    ],
                    [
                        'name' => __('app.pages.privacy'),
                        'icon' => 'file-text',
                        'url'  => ['blog-post.page', 'privacy'],
                    ],
                    [
                        'name' => __('app.pages.terms'),
                        'icon' => 'file-text',
                        'url'  => ['blog-post.page', 'terms'],
                    ],
                    [
                        'name' => __('app.pages.news'),
                        'icon' => 'file-text',
                        'url'  => ['blog-post.blogs', 'news'],
                    ],
                ],
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
