@php
use Sdkconsultoria\Base\Widgets\Form\ActiveField;
@endphp

<?= ActiveField::Input($model, 'name')?>
<?= ActiveField::Input($model, 'description')->textArea()?>

<h2>@lang('blog::blog.keys')</h2>
<keys-component
    :items='<?=json_encode($model->getKeys())?>'
    name="<?= $model->getLabel('name') ?>"
    add_text="<?= __('base::messages.add')?>"
    create_url="{{route('blog.addkey', ['id' => $model->id])}}"
    delete_url="{{route('blog.removekey', ['id' => $model->id])}}"
    csrf_token="{{csrf_token()}}"
    delete_element="<?= __('base::messages.delete_item') ?>"
    delete_continue="<?= __('base::messages.continue') ?>"
    delete_cancel="<?= __('base::messages.cancel') ?>"
    delete_deleted="<?= __('base::messages.deleted') ?>"
    delete_deleted_text="<?= __('base::messages.element_deleted') ?>"
></keys-component>

<h2>@lang('blog::blog.sizes')</h2>
<sizes-component
    :items='<?=json_encode($model->getSizes())?>'
    name="<?= $model->getLabel('name') ?>"
    height="<?= __('blog::blog.images.height')?>"
    width="<?= __('blog::blog.images.width')?>"
    quality="<?= __('blog::blog.images.quality')?>"
    add_text="<?= __('base::messages.add')?>"
    create_url="{{route('blog.addsize', ['id' => $model->id])}}"
    delete_url="{{route('blog.removesize', ['id' => $model->id])}}"
    csrf_token="{{csrf_token()}}"
    delete_element="<?= __('base::messages.delete_item') ?>"
    delete_continue="<?= __('base::messages.continue') ?>"
    delete_cancel="<?= __('base::messages.cancel') ?>"
    delete_deleted="<?= __('base::messages.deleted') ?>"
    delete_deleted_text="<?= __('base::messages.element_deleted') ?>"
></sizes-component>

<h2>@lang('blog::blog.images_types')</h2>
<div class="form-group">
    <image-types-component
        :items='<?=json_encode($model->getImagesTypes())?>'
        name="<?= $model->getLabel('name') ?>"
        add_text="<?= __('base::messages.add')?>"
        create_url="{{route('blog.addimage', ['id' => $model->id])}}"
        delete_url="{{route('blog.removeimage', ['id' => $model->id])}}"
        csrf_token="{{csrf_token()}}"
        delete_element="<?= __('base::messages.delete_item') ?>"
        delete_continue="<?= __('base::messages.continue') ?>"
        delete_cancel="<?= __('base::messages.cancel') ?>"
        delete_deleted="<?= __('base::messages.deleted') ?>"
        delete_deleted_text="<?= __('base::messages.element_deleted') ?>"
    ></image-types-component>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">@lang('base::messages.save')</button>
</div>