@php
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Messages\Error;
use Sdkconsultoria\Base\Widgets\Form\ActiveField;

@endphp
@extends('base::layouts.main')

@section('title_tab', __('base::app.update', ['name' => '"'.__('app.pages.'.$name).'"']))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'Active' => __('base::app.update', ['name' => '"'.__('app.pages.'.$name).'"']),
        ]) ?>
@endsection

@section('content')
    @card({{__('base::app.update', ['name' => '"'.__('app.pages.'.$name).'"'])}})
    <?= Error::generate($errors) ?>
    <form enctype="multipart/form-data" action="{{route('blog-post.page', $model->identifier)}}" method="post" novalidate>
        @csrf
        @method('PUT')
        <div class="form-group row">
          <div class="col-md-12">
            <?= ActiveField::Input($model, 'title')?>
          </div>
          <div class="col-md-12">
            <?= ActiveField::Input($model, 'subtitle')?>
          </div>
        </div>
        @include('blog::blog-post._form', ['page' => true])
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
