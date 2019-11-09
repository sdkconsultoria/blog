@php
use Sdkconsultoria\Base\Widgets\Grid\GridView;
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Messages\Alert;
@endphp
@extends('base::layouts.main')

@section('title_tab', __('blog::blog.blogs'))


@section('breadcrumb')
    <?= BreadCrumb::generate([
        'Active'    => __('blog::blog.blogs'),
        ]) ?>
@endsection

@section('content')
    @card({{__('blog::blog.available_blogs')}})
    <div class="form-group">
        <a href="{{route('blog.create')}}" class="btn btn-primary"> @lang('blog::blog.create_blog') </a>
    </div>
    <?= Alert::generate() ?>
    <?= GridView::generate([
        'model' => $model,
        'models' => $models,
        'route' => 'blog',
        'attributes' => [
            'name',
            'created_at',
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->createdBy->name;
                }
            ],
            'status',
        ]
    ])?>
    @endcard
@endsection
