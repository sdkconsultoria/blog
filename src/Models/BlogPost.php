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
           'blogs_id'         => 'required',
           'language'         => 'required',
           'title'            => 'required',
           'meta_author'      => 'required',
           'meta_description' => 'required',
           'meta_keywords'    => 'required',
           'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ];
   }

   /**
    * Get the validation rules that apply to the request in update.
    *
    * @return array
    */
   public static function rulesUpdate()
   {
       $validate = Self::rules();
       unset($validate['blogs_id']);
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
           'created_at'       => __('base::attributes.created_at'),
           'updated_at'       => __('base::attributes.updated_at'),
           'created_by'       => __('base::attributes.created_by'),
           'status'           => __('base::attributes.status'),
           'name'             => __('base::attributes.name'),
           'seoname'          => __('base::attributes.sename'),
           'description'      => __('base::attributes.description'),
           'blog_posts_id'    => __('blog::blog.blog_posts_id'),
           'blogs_id'         => __('blog::blog.blogs_id'),
           'language'         => __('base::messages.language'),
           'title'            => __('base::messages.title'),
           'meta_author'      => __('blog::blog.meta_author'),
           'meta_description' => __('blog::blog.meta_description'),
           'meta_keywords'    => __('blog::blog.meta_keywords'),
           'images'           => __('base::attributes.images'),
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
           'blogs_id' => [
               'join' => 'blogs',
               'column' => 'name'
           ]
       ], $attributes);
   }

   public function createdBy()
   {
       return $this->hasOne('App\User', 'id', 'created_by');
   }

   public function blog()
   {
       return $this->hasOne('Sdkconsultoria\Blog\models\Blog', 'id', 'blogs_id');
   }

   public function images()
   {
       return $this->hasMany('Sdkconsultoria\Blog\models\BlogImage', 'blog_posts_id', 'id');
   }

   public function save(array $options = [])
   {
       if (empty($this->id) or $this->isDirty('name')) {
           $this->seoname = \Sdkconsultoria\Base\Helpers\Helpers::toSeo($this->name);
       }
       parent::save($options);
   }

   public function getKeys()
   {
       return $this->hasMany('Sdkconsultoria\Blog\models\BlogKey', 'blog_posts_id', 'id');
   }

   public function getKeyValue($key)
   {
       return $this->getKeys()->where('name', $key)->first();
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
       $model->blog_posts_id = $this->id;
       $model->created_by = auth()->user()->id;
       $model->name = $key['name'];
       $model->seoname = $key['name'];
       $model->value = $value;
       $model->seovalue = $value;
       $model->save();
   }
}
