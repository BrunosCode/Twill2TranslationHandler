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
    @if(session('status'))
        <p style="color:green; margin-bottom:12px;">{{ session('status') }}</p>
    @endif
    @if(session('error'))
        <p style="color:red; margin-bottom:12px;">{{ session('error') }}</p>
    @endif

    <fieldset style="margin-top:24px;">
        <form method="POST" action="{{ route('admin.translations.translationGroups.exportCsv', $item->id) }}">
            @csrf
            <a17-button variant="action" ="small" type="submit" style="cursor:pointer;">
                Download CSV
            </a17-button>
        </form>
    </fieldset>

    {{-- <fieldset style="margin-top:24px;">
        <legend>CSV Import</legend>

        <form method="POST"
              action="{{ route('admin.translations.translationGroups.importCsv', $item->id) }}"
              enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:12px;">
                <input type="file" name="csv_file" accept=".csv,text/csv" required
                       style="font-size:0.85em;">
                @error('csv_file')
                    <p style="color:red; font-size:0.8em; margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>
            <a17-button variant="action" size="small" type="submit" style="cursor:pointer;">
                Import CSV
            </a17-button>
        </form>
    </fieldset> --}}
@stop
