@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'name' => 'prefix',
        'label' => 'Prefix',
        'disabled' => true,
    ])

    @foreach($item->translation_items as $translation)
        @formField('input', [
            'name' => 'trans_' . $translation->id,
            'label' => $translation->key,
            'translated' => true,
            'type' => 'textarea',
        ])
    @endforeach
@stop

@section('sideFieldset')
    @formField('checkbox', [
        'name' => 'allow_empty',
        'label' => 'Allow saving empty values',
    ])

    <div style="margin-top:24px;">
        <a class="button button--action" href="{{ route('admin.translations.translationGroups.exportCsv', $item->id) }}">
            Download CSV
        </a>
    </div>
@stop
