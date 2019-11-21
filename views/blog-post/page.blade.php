@php
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Messages\Error;
use Sdkconsultoria\Base\Widgets\Form\ActiveField;

@endphp
@extends('base::layouts.main')

@section('title_tab', __('blog::blog.create_blog_post'))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'blog.index' => __('blog::blog.blog_post'),
        'Active'     => __('blog::blog.edit_blog_post'),
        ]) ?>
@endsection

@section('content')
    @card({{__('blog::blog.edit_blog_post')}})
    <?= Error::generate($errors) ?>
    <form enctype="multipart/form-data" action="{{route('blog-post.page', $model->identifier)}}" method="post" novalidate>
        @csrf
        @method('PUT')
        <div class="form-group row">
            <div class="col-md-12">
                <?= ActiveField::Input($model, 'title')?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <?= ActiveField::Input($model, 'description')->textArea(['class' => 'summernote'])?>
            </div>
        </div>

         <button style="display:none" id="edit" type="button"></button>
         <button style="display:none" id="save" type="button"></button>

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

        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <?= ActiveField::Input($model, 'images')->fileInput(['multiple'=>true])?>
                </div>
            </div>
            <br>
            <?= ActiveField::Input($model, '')->keyValues($model->blog->getKeys()) ?>
            <br>
            <images-component
               image="<?= __('base::attributes.image') ?>"
               type="<?= __('base::attributes.type') ?>"
               alt="<?= __('base::attributes.alt') ?>"
               menu="<?= __('base::attributes.alt') ?>"
               :items='<?=json_encode($model->images)?>'
               :images_types='<?=json_encode($model->blog->getImagesTypes())?>'
               save_url="{{route('blog-post.saveimage', ['id' => $model->id])}}"
               delete_url="{{route('blog-post.removeimage', ['id' => $model->id])}}"
               csrf_token="{{csrf_token()}}"
               delete_element="<?= __('base::messages.delete_item') ?>"
               delete_continue="<?= __('base::messages.continue') ?>"
               delete_cancel="<?= __('base::messages.cancel') ?>"
               delete_deleted="<?= __('base::messages.deleted') ?>"
               delete_deleted_text="<?= __('base::messages.element_deleted') ?>"
               save_saved="<?= __('base::messages.saved') ?>"
               save_saved_text="<?= __('base::messages.saved_element') ?>"
           ></images-component>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">@lang('base::messages.save')</button>
        </div>
    </form>
    @endcard()
@endsection
@section('custom_scripts')
    <script type="text/javascript">
    (function(window, document, $) {
        'use strict';
        // Basic Summernote
        $('.summernote').summernote({
            height: 250,   //set editable area's height
        });
    })(window, document, jQuery);
    </script>
@endsection
