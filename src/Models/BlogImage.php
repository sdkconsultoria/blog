<?php

namespace Sdkconsultoria\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    public function blogPost()
    {
        return $this->hasOne('Sdkconsultoria\Blog\Models\BlogPost', 'id', 'blog_posts_id');
    }

    public function save(array $options = [])
    {
        $this->sizes = $this->blogPost->sizes;
        parent::save($options);
    }
}
