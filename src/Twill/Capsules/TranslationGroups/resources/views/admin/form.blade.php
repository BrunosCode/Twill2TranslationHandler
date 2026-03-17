@extends('twill::layouts.form')

@section('contentFields')
{{-- @dd($form_fields) --}}
    @formField('input', [
        'name' => 'description',
        'label' => 'Description',
        'translated' => true,
        'textarea' => true,
    ])

    @foreach ($form_fields as $field)
        @php
            $label = empty($field['translations']['value'][app()->getLocale()])
                ? $field['key']
                : $field['translations']['value'][app()->getLocale()];
        @endphp

        @formField('input', [
            'name' => $field['key'],
            'label' => $label,
            'translated' => true,
            'textarea' => true,
            'note' => $field['key'],
        ])
    @endforeach
@stop
