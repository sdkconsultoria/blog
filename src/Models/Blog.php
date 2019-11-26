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
}
