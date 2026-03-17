<?php

namespace BrunosCode\Twill2TranslationHandler;

use BrunosCode\TranslationHandler\Collections\TranslationCollection;
use BrunosCode\TranslationHandler\DatabaseHandler as BaseDatabaseHandler;
use BrunosCode\TranslationHandler\Interfaces\DatabaseHandlerInterface;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

class DatabaseHandler extends BaseDatabaseHandler implements DatabaseHandlerInterface
{
    public function handleInsert(Connection $db, TranslationCollection $translations, ?string $filename = null, ?Collection $dbKeys = null): int
    {
        $this->syncGroups($translations);

        return parent::handleInsert($db, $translations, $filename, $dbKeys);
    }

    protected function syncGroups(TranslationCollection $translations): void
    {
        $delimiter = config('translation-handler.keyDelimiter', '.');
        $prefixes = collect();

        $translations->pluck('key')->unique()->each(function (string $key) use ($prefixes, $delimiter) {
            $parts = explode($delimiter, $key);

            // Create groups for all prefixes except the full key
            // e.g. "file.key1.key2.key3" -> groups: "file", "file.key1", "file.key1.key2"
            array_pop($parts);

            $prefix = '';
            foreach ($parts as $part) {
                $prefix = $prefix === '' ? $part : $prefix.$delimiter.$part;
                $prefixes[$prefix] = true;
            }
        });

        $prefixes->keys()->each(function (string $prefix) {
            TranslationGroup::firstOrCreate(['prefix' => $prefix]);
        });
    }
}
