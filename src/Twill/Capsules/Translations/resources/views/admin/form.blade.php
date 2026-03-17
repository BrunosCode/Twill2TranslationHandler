@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'name' => 'key',
        'label' => 'Key',
        'disabled' => true,
    ])

    @formField('input', [
        'name' => 'value',
        'label' => 'Value',
        'translated' => true,
        'required' => true,
        'type' => 'textarea',
    ])
@stop
