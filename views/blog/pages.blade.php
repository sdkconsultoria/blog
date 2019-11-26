@php
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Messages\Error;
use Sdkconsultoria\Base\Widgets\Form\ActiveField;
use Sdkconsultoria\Base\Widgets\Grid\GridView;

@endphp
@extends('base::layouts.main')

@section('title_tab', __('base::app.update', ['name' => $name]))

@section('breadcrumb')
    <?= BreadCrumb::generate([
        'Active' => __('base::app.update', ['name' => $name]),
        ]) ?>
@endsection

@section('content')
    @card({{__('base::app.update', ['name' => $name])}})
    <?= Error::generate($errors) ?>

    <div class="form-group">
        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#large">
            {{__('blog::blog.add-pages', ['page' => $name])}}
        </button>
    </div>

   <!-- Modal -->
   <div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" style="display: none;" aria-hidden="true">
       <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h4 class="modal-title" id="myModalLabel17">Basic Modal</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">×</span>
                   </button>
               </div>
               <form enctype="multipart/form-data" action="{{route('blog-post.pages', $model->identifier)}}" method="post" novalidate>
                   <div class="modal-body">
                       @csrf
                       <div class="form-group">
                           <?= ActiveField::Input($post, 'name')?>
                       </div>
                       <div class="form-group">
                           <?= ActiveField::Input($post, 'description')->textArea()?>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">@lang('base::messages.close')</button>
                       <button type="submit" class="btn btn-primary">@lang('base::messages.save')</button>
                   </div>
               </form>
           </div>
       </div>
   </div>

    <?= GridView::generate([
        'model' => $post,
        'models' => $posts,
        'route' => 'blog',
        'attributes' => [
            'name',
            'description',
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
    @endcard()
@endsection
