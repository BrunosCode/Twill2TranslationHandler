@extends('twill::layouts.form')

@section('contentFields')
{{-- @dd($form_fields) --}}
    @formField('input', [
        'name' => 'value',
        'label' => 'Translation',
        'translated' => true,
        'textarea' => true,
    ])
@stop
