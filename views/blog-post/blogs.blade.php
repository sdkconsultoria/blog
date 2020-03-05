@php
    use Sdkconsultoria\Base\Widgets\Grid\GridView;
    use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
    use Sdkconsultoria\Base\Widgets\Messages\Alert;
@endphp
@extends('base::layouts.main')

@section('title_tab', $blog->name)

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'Active'    => $blog->name,
        ]) ?>
@endsection

@section('content')
    @card({{$blog->name}})
    <div class="form-group">
        <a href="{{route('blog-post.create-blog', $blog->id)}}" class="btn btn-primary"> @lang('base::app.create', ['item' => $blog->name]) </a>
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
