<?php

namespace Sdkconsultoria\Blog\Models;

use Sdkconsultoria\Base\Models\ResourceModel;
use Sdkconsultoria\Blog\Models\BlogKey;

class BlogPost extends ResourceModel
{

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public static function rules($params)
   {
       return [
           'name'             => 'required|min:3',
           'description'      => 'required',
           'blog_id'         => 'required',
           'language'         => 'required',
           'title'            => 'required',
           'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ];
   }

   /**
    * Get the validation rules that apply to the request in update.
    *
    * @return array
    */
   public static function rulesUpdate($params)
   {
       $validate = Self::rules($params);
       unset($validate['blog_id']);
       return $validate;
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
           'name'             => __('base::attributes.name'),
           'seoname'          => __('base::attributes.seoname'),
           'description'      => __('base::attributes.description'),
           'blog_post_id'    => __('blog::blog.blog_post_id'),
           'blog_id'         => __('blog::blog.blog_id'),
           'language'         => __('base::messages.language'),
           'title'            => __('base::messages.title'),
           'subtitle'         => __('base::messages.subtitle'),
           'meta_author'      => __('blog::blog.meta_author'),
           'meta_description' => __('blog::blog.meta_description'),
           'meta_keywords'    => __('blog::blog.meta_keywords'),
           'images'           => __('base::attributes.images.items'),
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
           'name',
           'blog_id' => [
               'join' => 'blogs',
               'column' => 'name'
           ]
       ], $attributes);
   }

   public function blog()
   {
       return $this->hasOne('Sdkconsultoria\Blog\Models\Blog', 'id', 'blog_id');
   }

   public function images()
   {
       return $this->hasMany('Sdkconsultoria\Blog\Models\BlogImage', 'blog_post_id', 'id');
   }

   public function getImage($type = false, $limit = true)
   {
       $response = $this->images();
       if ($type) {
           if (is_array($type)) {
               $response  = $response->whereIn('type', $type);
           } else {
               $response  = $response->where('type', $type);
           }

       }

       if ($limit) {
           return $response->first();
       }

       return $response->get();

   }

   public function save(array $options = [])
   {
       $this->generateSeoname();
       if (empty($this->id)) {
           $this->sizes = $this->blog->sizes;
       }
       parent::save($options);
   }

    public function getDefaultKeys(){
        if ($this->keys) {
            $items = unserialize($this->keys);
            if ($items) {
                return $items;
            }
        }

        return $this->blog->getKeys();
    }

   public function getKeys()
   {
       return $this->hasMany('Sdkconsultoria\Blog\Models\BlogKey', 'blog_post_id', 'id');
   }

   public function getKeyValue($key)
   {
       return $this->getKeys()->where('seoname', $key)->first();
   }

   public function addKey($key, $value)
   {
       if (empty($value)) {
           return false;
       }

       $model = $this->getKeyValue($key['name']);
       if ($model) {
           $model->value = $value;
           $model->save();
           return;
       }

       $model = new BlogKey();
       $model->blog_post_id = $this->id;
       $model->created_by = auth()->user()->id;
       $model->name = $key['name'];
       $model->value = $value;
       $model->save();
   }

   public function saveKey($label, $value)
   {
       $key =  BlogKey::where('blog_post_id', $this->id)->where('name', $label)->first();

       if ($key) {
           $key->value = $value;
       }else{
           $key                = new BlogKey();
           $key->blog_post_id = $this->id;
           $key->created_by    = auth()->user()->id;
           $key->name          = $label;
           $key->value         = $value;
       }

       $key->save();
   }
}
