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

@section('sideFieldset')
    <div style="margin-top:24px;">
        <a class="button button--action" href="{{ route('admin.translations.translationGroups.exportCsv', $item->id) }}">
            Download CSV
        </a>
    </div>
@stop
