<?php

namespace BrunosCode\Twill2TranslationHandler;

use BrunosCode\TranslationHandler\Collections\TranslationCollection;
use BrunosCode\TranslationHandler\Data\Translation;
use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\DatabaseHandler as BaseDatabaseHandler;
use BrunosCode\TranslationHandler\Interfaces\DatabaseHandlerInterface;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Models\TranslationGroup;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

class DatabaseHandler extends BaseDatabaseHandler implements DatabaseHandlerInterface
{
  public function __construct(
    TranslationOptions $options
  ) {
    parent::__construct($options);
  }

  public function handleInsert(Connection $db, TranslationCollection $translations, ?string $filename = null, ?Collection $dbKeys = null): int
  {
    $groups = collect([]);

    $translations->pluck('key')->unique()->each(function (string $key) use (&$groups) {
      $prefix = '';
      foreach (explode($this->options->keyDelimiter, $key) as $partialKey) {
        if (empty($prefix)) {
          $prefix .= $partialKey;
        } else {
          $prefix .= $this->options->keyDelimiter . $partialKey;
        }

        $groups[$prefix] = true;
      }
    });

    $groups->keys()->each(function (string $prefix) {
      $groupModel = TranslationGroup::updateOrCreate(['prefix' => $prefix]);

      foreach ($this->options->locales as $locale) {
        $groupModel->translations()->updateOrCreate(['locale' => $locale]);
      }
    });

    return parent::handleInsert($db, $translations, $filename, $dbKeys);
  }
}
