@php
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Form\ActiveField;
use Sdkconsultoria\Base\Widgets\Messages\Error;
use Sdkconsultoria\Blog\Models\Blog;

@endphp
@extends('base::layouts.main')

@section('title_tab', __('blog::blog.create_blog_post'))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'blog.index' => __('blog::blog.blog_posts'),
        'Active'     => __('blog::blog.create_blog_post'),
        ]) ?>
@endsection

@section('content')
    @card({{__('blog::blog.create_blog')}})
    <?= Error::generate($errors) ?>
    <form enctype="multipart/form-data" action="{{route('blog-post.store')}}" method="post">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <?= ActiveField::Input($model, 'blogs_id')
                ->select(Blog::getSelect(), [
                    'id' => 'blogs_id',
                    'data-url' => route('blog-post.create-blog', 'default')
                    ])?>            </div>
            <div class="col-md-6">
                <?= ActiveField::Input($model, 'language')->select(config('base.languages'))?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <?= ActiveField::Input($model, 'name')?>
            </div>
            <div class="col-md-6">
                <?= ActiveField::Input($model, 'title')?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <?= ActiveField::Input($model, 'description')->textArea(['class' => 'summernote'])?>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <?= ActiveField::Input($model, 'meta_author')?>
                </div>
                <div class="col-md-4">
                    <?= ActiveField::Input($model, 'meta_description')?>
                </div>
                <div class="col-md-4">
                    <?= ActiveField::Input($model, 'meta_keywords')?>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">@lang('base::messages.save')</button>
            </div>
        </div>
    </form>
    @endcard()
@endsection

@section('custom_scripts')
    <script type="text/javascript">
    (function(window, document, $) {
        'use strict';
        $('.summernote').summernote({
            height: 250,   //set editable area's height
        });
    })(window, document, jQuery);
    </script>
@endsection
