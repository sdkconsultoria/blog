@php
use Sdkconsultoria\Base\Widgets\Form\ActiveField;
use Sdkconsultoria\Blog\Models\Blog;
@endphp

<div class="form-group row">
    <div class="col-md-6">
        <?= ActiveField::Input($model, 'blogs_id')
        ->select(Blog::getSelect(), [
            'disabled' => 'true',
            'id' => 'blogs_id',
            'readOnly' => true,
            'data-url' => route('blog-post.create-blog', 'default')
            ])?>
    </div>
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