@php
use Sdkconsultoria\Base\Widgets\Information\BreadCrumb;
use Sdkconsultoria\Base\Widgets\Messages\Error;
use Sdkconsultoria\Base\Widgets\Form\ActiveField;

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


    @endcard()
@endsection
