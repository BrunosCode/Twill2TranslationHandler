@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'name' => 'prefix',
        'label' => 'Prefix',
        'disabled' => true,
    ])

    @formField('repeater', [
        'type' => 'translation_item',
    ])
@stop
