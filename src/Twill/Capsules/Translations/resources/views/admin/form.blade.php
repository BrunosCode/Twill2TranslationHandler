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
        'type' => 'textarea',
    ])
@stop

@section('sideFieldset')
    @formField('checkbox', [
        'name' => 'allow_empty',
        'label' => 'Allow saving empty values',
    ])
@stop
