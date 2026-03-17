<?php

namespace BrunosCode\Twill2TranslationHandler\Http\Controllers;

use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\Facades\TranslationHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class TranslationToolsController extends Controller
{
    public function index()
    {
        return view('twill-2-translation-handler::tools.index');
    }

    public function exportToPhp(Request $request)
    {
        try {
            TranslationHandler::export(TranslationOptions::DB, TranslationOptions::PHP, true);

            return redirect()->back()->with('status', 'Translations exported to PHP files successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function importFromPhp(Request $request)
    {
        try {
            TranslationHandler::import(TranslationOptions::PHP, TranslationOptions::DB, true);

            return redirect()->back()->with('status', 'Translations imported from PHP files successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function exportToCsv(Request $request)
    {
        try {
            TranslationHandler::export(TranslationOptions::DB, TranslationOptions::CSV, true);

            $csvPath = config('translation-handler.csvPath', storage_path('lang'));
            $csvFileName = config('translation-handler.csvFileName', 'translations') . '.csv';
            $filePath = $csvPath . DIRECTORY_SEPARATOR . $csvFileName;

            if (! File::exists($filePath)) {
                return redirect()->back()->with('error', 'CSV file was not generated.');
            }

            return response()->download($filePath, $csvFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function importFromCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $csvPath = config('translation-handler.csvPath', storage_path('lang'));
            $csvFileName = config('translation-handler.csvFileName', 'translations') . '.csv';

            File::ensureDirectoryExists($csvPath);

            $request->file('csv_file')->move($csvPath, $csvFileName);

            TranslationHandler::import(TranslationOptions::CSV, TranslationOptions::DB, true);

            File::delete($csvPath . DIRECTORY_SEPARATOR . $csvFileName);

            return redirect()->back()->with('status', 'Translations imported from CSV successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
