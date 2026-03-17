@extends('twill::layouts.free')

@section('customPageContent')
    <div style="padding: 20px 0;">
        <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 24px;">Import / Export</h2>

        @if(session('status'))
            <div style="background: #c6f6d5; border: 1px solid #9ae6b4; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #22543d;">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fed7d7; border: 1px solid #feb2b2; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #742a2a;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fed7d7; border: 1px solid #feb2b2; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; color: #742a2a;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            {{-- PHP Section --}}
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
                <h3 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">PHP Files</h3>
                <p style="font-size: 13px; color: #718096; margin-bottom: 20px;">
                    Import/export translations between the database and Laravel PHP language files.
                </p>

                <div style="display: flex; gap: 12px;">
                    <form method="POST" action="{{ route('admin.translations.translationTools.importFromPhp') }}">
                        @csrf
                        <button type="submit" class="btn" style="background: #3182ce; color: #fff; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                            Import from PHP
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.translations.translationTools.exportToPhp') }}">
                        @csrf
                        <button type="submit" class="btn" style="background: #2f855a; color: #fff; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                            Export to PHP
                        </button>
                    </form>
                </div>
            </div>

            {{-- CSV Section --}}
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
                <h3 style="font-size: 15px; font-weight: 600; margin-bottom: 8px;">CSV</h3>
                <p style="font-size: 13px; color: #718096; margin-bottom: 20px;">
                    Export translations to CSV for download, or import from a CSV file.
                </p>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <form method="POST" action="{{ route('admin.translations.translationTools.exportToCsv') }}">
                        @csrf
                        <button type="submit" class="btn" style="background: #2f855a; color: #fff; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                            Download CSV
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.translations.translationTools.importFromCsv') }}" enctype="multipart/form-data">
                        @csrf
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="file" name="csv_file" accept=".csv" required
                                style="font-size: 13px; padding: 6px; border: 1px solid #e2e8f0; border-radius: 4px;">
                            <button type="submit" class="btn" style="background: #3182ce; color: #fff; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                Import CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
