@php
    use Sdkconsultoria\Base\Widgets\Grid\GridView;
    use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
    use Sdkconsultoria\Base\Widgets\Messages\Alert;
@endphp
@extends('base::layouts.main')

@section('title_tab', __('blog::blog.blog_posts'))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'Active'    => __('blog::blog.available_blog_post'),
        ]) ?>
@endsection

@section('content')
    @card({{__('blog::blog.available_blog_post')}})
    <div class="form-group">
        <a href="{{route('blog-post.create')}}" class="btn btn-primary"> @lang('blog::blog.create_blog_post') </a>
    </div>
    <?= Alert::generate() ?>
    <?= GridView::generate([
        'model' => $model,
        'models' => $models,
        'route'  => 'blog-post',
        'attributes' => [
            'name',
            'created_at',
            'language',
            [
                'attribute' => 'blog_id',
                'value' => function($model){
                    return $model->blog->name;
                }
            ],
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
