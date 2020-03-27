<?php
namespace Sdkconsultoria\Blog\Traits;

use Sdkconsultoria\Blog\Models\{BlogPost, Blog};

/**
 *
 */
trait BlogControllerTrait
{
    protected function getPost(array $params)
    {
        $params['blog_post']            = $params['blog_post']??'';
        $params['blog_post_identifier'] = $params['blog_post_identifier']??'identifier';
        $params['blog']                 = $params['blog']??'pages';
        $params['blog_identifier']      = $params['blog_identifier']??'identifier';

        $blog      = $this->getBlog($params['blog'], $params['blog_identifier']);
        $blog_post = BlogPost::where($params['blog_post_identifier'], $params['blog_post'])->where('blog_id', $blog->id)->with('images')->first();

        return $blog_post;
    }

    protected function getSinglePost($post, $key = 'seoname')
    {
        return BlogPost::where($key, $post)->where('status', BlogPost::STATUS_ACTIVE)->with('images')->first();
    }

    protected function getBlog($blog, $identifier = 'identifier')
    {
        return Blog::where($identifier, $blog)->first();
    }

    protected function getPosts($blog)
    {
        $blog       = $this->getBlog($blog);
        $blog_posts = BlogPost::where('blog_id', $blog->id)->where('status', BlogPost::STATUS_ACTIVE)->get();

        return $blog_posts;
    }

}

?>
