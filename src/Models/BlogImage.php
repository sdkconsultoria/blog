<?php

namespace Sdkconsultoria\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Sdkconsultoria\Base\Helpers\Images;

class BlogImage extends Model
{
    public function blogPost()
    {
        return $this->hasOne('Sdkconsultoria\Blog\Models\BlogPost', 'id', 'blog_posts_id');
    }

    public function save(array $options = [])
    {
        if (empty($this->id)) {
            $this->sizes = $this->blogPost->sizes;
        }
        parent::save($options);
    }

    public function getSizesAttribute($value)
    {
        return unserialize($value);
    }

    public function removeImage($rm_original = true)
    {
        Images::removeImage('blogs/'.$this->blog_posts_id.'/', $this->id, $this->extension, $rm_original);
    }

    public function convertImage()
    {
        Images::convertImage('blogs/'.$this->blog_posts_id.'/', $this->id, $this->extension, $this->sizes);
    }
}
