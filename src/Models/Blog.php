<?php

namespace Sdkconsultoria\Blog\Models;

use Sdkconsultoria\Base\Models\ResourceModel;

class Blog extends ResourceModel
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules($params)
    {
        return [
            'name' => 'required|min:3',
            'description' => 'required',
        ];
    }

    /**
     * Get client attributes values.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        return array_merge($attributes, [
            'name'         => __('base::attributes.name'),
            'seoname'      => __('base::attributes.sename'),
            'title'        => __('base::messages.title'),
            'subtitle'     => __('base::messages.subtitle'),
            'description'  => __('base::attributes.description'),
            'sizes'        => __('blog::blog.sizes'),
            'keys'         => __('blog::blog.keys'),
            'images_types' => __('blog::blog.images_types'),
            'parent_id' => __('blog::blog.parent_id'),
        ]);
    }

    /**
     * Get attributes for search.
     *
     * @return array
     */
    public function getFiltersAttribute()
    {
        $attributes = parent::getFiltersAttribute();
        return array_merge([
            'name'
        ], $attributes);
    }

    public function getSizes(){
        $items = unserialize($this->sizes);
        if ($items) {
            return $items;
        }
        return [];
    }

    public function getKeys(){
        $items = unserialize($this->keys);
        if ($items) {
            return $items;
        }
        return [];
    }

    public function getImagesTypes(){
        $items = unserialize($this->images_types);
        if ($items) {
            return $items;
        }
        return [];
    }

    public function save(array $options = [])
    {
        $this->generateSeoname();
        parent::save($options);
    }

    /**
     * Scope a query to only parents blogs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->whereNull('parent_id');
    }

    public function childs(){
        return $this->hasMany('Sdkconsultoria\Blog\Models\Blog', 'parent_id', 'id')->where('status', self::STATUS_ACTIVE);
    }

    public function childsForce(){
        $posts = $this->posts();

        if (!$posts->count()) {
            $childs = $this->childs;
            foreach ($childs as $child) {
                $child_posts = $child->childsForce();
                if ($child_posts) {
                    return $child_posts;
                }
            }
        }

        return $posts;
    }

    public function parent()
    {
        return $this->belongsTo('Sdkconsultoria\Blog\Models\Blog', 'parent_id', 'id');
    }

    public function translate()
    {
        return $this->hasOne('Sdkconsultoria\Blog\Models\Blog', 'blog_id', 'id');
    }

    public function posts(){
        return $this->hasMany('Sdkconsultoria\Blog\Models\BlogPost', 'blog_id', 'id')->where('status', self::STATUS_ACTIVE);
    }

    public function getBreadcrumb($current = false, $route = 'blog-category')
    {
        $categories = [];
        foreach ($this->getParentCategories($current) as $key => $category) {
            $categories[$category->name] = [$route, $category->seoname];
        }

        return $categories;
    }

    public function getParentCategories($current)
    {
        return array_reverse($this->getParentCategory(false, $current));
    }

    public function getParentCategory($blog = false, $current = false)
    {
        if (!$blog) {
            $blog = $this;
            if ($current) {
                $categories = [$blog];
            }else{
                $categories = [];
            }
        }else{
            $categories = [$blog];
        }


        if ($blog->parent_id) {
            $categories = array_merge($categories, $this->getParentCategory($blog->parent));
        }

        return $categories;
    }
}
