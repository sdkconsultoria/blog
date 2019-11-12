@php
use Sdkconsultoria\Base\Widgets\Information\{BreadCrumb};
use Sdkconsultoria\Base\Widgets\Messages\Error;
@endphp

@extends('base::layouts.main')

@section('title_tab', __('blog::blog.create_blog'))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'blog.index' => __('blog::blog.blogs'),
        'Active'     => __('blog::blog.create_blog'),
        ]) ?>
@endsection

@section('content')
    @card({{__('blog::blog.create_blog')}})
    <?= Error::generate($errors) ?>
    <form action="{{route('blog.store')}}" method="post" novalidate>
        @csrf
        @include('blog::blog._form')
    </form>
    @endcard()
@endsection
