@php
    use Sdkconsultoria\Base\Widgets\Grid\Details;
    use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
@endphp
@extends('base::layouts.main')

@section('title_tab', __('blog::blog.detail_blog_post'))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'blog.index' => __('blog::blog.blog_post'),
        'Active' => __('blog::blog.detail_blog_post'),
        ]) ?>
@endsection

@section('content')
    @card({{__('blog::blog.detail_blog_post')}})
        <?= Details::generate($model, [
            'name',
            'description',
            'created_at',
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->createdBy->username;
                }
            ],
            ])?>

            <h2>@lang('blog::blog.extra_keys')</h2>
            <table class="table">
                @foreach ($model->getKeys as $key => $value)
                    <tr>
                        <th>{{$value->name}}</th>
                        <td>{{$value->value}}</td>
                    </tr>
                @endforeach
            </table>
    @endcard()
@endsection
