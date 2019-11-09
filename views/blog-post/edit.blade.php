@php
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Messages\Error;
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
    <form enctype="multipart/form-data" action="{{route('blog-post.update', $model->seoname)}}" method="post">
        @csrf
        @method('PUT')
        @include('blog::blog-post._form')
    </form>
    @endcard()
@endsection
