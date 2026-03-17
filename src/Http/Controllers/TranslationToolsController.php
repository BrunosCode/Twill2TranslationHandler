<?php

namespace BrunosCode\TwillTranslationHandler\Http\Controllers;

use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\Facades\TranslationHandler;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class TranslationToolsController extends Controller
{
    public function index()
    {
        return view('twill-translation-handler::tools.index');
    }

    public function exportToPhp(Request $request)
    {
        try {
            TranslationHandler::export(TranslationOptions::DB, TranslationOptions::PHP, true);

            return redirect()->back()->with('status', 'Translations exported to PHP files successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: '.$e->getMessage());
        }
    }

    public function importFromPhp(Request $request)
    {
        try {
            TranslationHandler::import(TranslationOptions::PHP, TranslationOptions::DB, true);

            return redirect()->back()->with('status', 'Translations imported from PHP files successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: '.$e->getMessage());
        }
    }

    public function exportToCsv(Request $request)
    {
        try {
            $csvPath = config('translation-handler.csvPath', storage_path('lang'));
            $csvFileName = config('translation-handler.csvFileName', 'translations').'.csv';
            $filePath = $csvPath.DIRECTORY_SEPARATOR.$csvFileName;

            // Remove any existing CSV to avoid merge failures with stale/malformed files.
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            TranslationHandler::export(TranslationOptions::DB, TranslationOptions::CSV, true);

            if (! File::exists($filePath)) {
                return redirect()->back()->with('error', 'CSV file was not generated.');
            }

            return response()->download($filePath, $csvFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: '.$e->getMessage());
        }
    }

    public function importFromCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $csvPath = config('translation-handler.csvPath', storage_path('lang'));
            $csvFileName = config('translation-handler.csvFileName', 'translations').'.csv';

            File::ensureDirectoryExists($csvPath);

            $request->file('csv_file')->move($csvPath, $csvFileName);

            TranslationHandler::import(TranslationOptions::CSV, TranslationOptions::DB, true);

            File::delete($csvPath.DIRECTORY_SEPARATOR.$csvFileName);

            return redirect()->back()->with('status', 'Translations imported from CSV successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: '.$e->getMessage());
        }
    }

    public function exportGroupCsv(Request $request, int $id)
    {
        $group = TranslationGroup::findOrFail($id);

        try {
            $csvPath = config('translation-handler.csvPath', storage_path('lang'));
            // CsvFileHandler always writes to {csvPath}/{csvFileName}.csv
            $defaultFilePath = $csvPath.DIRECTORY_SEPARATOR.config('translation-handler.csvFileName', 'translations').'.csv';

            // Remove stale CSV so the handler starts fresh.
            if (File::exists($defaultFilePath)) {
                File::delete($defaultFilePath);
            }

            TranslationHandler::setOption('fileNames', [$group->prefix])
                ->export(TranslationOptions::DB, TranslationOptions::CSV, true);

            TranslationHandler::resetOptions();

            if (! File::exists($defaultFilePath)) {
                return redirect()->back()->with('error', 'CSV file was not generated.');
            }

            $downloadName = str_replace('.', '-', $group->prefix).'.csv';

            return response()->download($defaultFilePath, $downloadName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            TranslationHandler::resetOptions();

            return redirect()->back()->with('error', 'Export failed: '.$e->getMessage());
        }
    }

    public function importGroupCsv(Request $request, int $id)
    {
        $group = TranslationGroup::findOrFail($id);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $csvPath = config('translation-handler.csvPath', storage_path('lang'));
            $csvFileName = config('translation-handler.csvFileName', 'translations').'.csv';

            File::ensureDirectoryExists($csvPath);

            $request->file('csv_file')->move($csvPath, $csvFileName);

            TranslationHandler::setOption('fileNames', [$group->prefix])
                ->import(TranslationOptions::CSV, TranslationOptions::DB, true);

            TranslationHandler::resetOptions();

            File::delete($csvPath.DIRECTORY_SEPARATOR.$csvFileName);

            return redirect()->back()->with('status', 'Translations imported from CSV successfully.');
        } catch (\Exception $e) {
            TranslationHandler::resetOptions();

            return redirect()->back()->with('error', 'Import failed: '.$e->getMessage());
        }
    }
}
