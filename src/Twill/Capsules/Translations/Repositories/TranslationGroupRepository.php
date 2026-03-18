<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRepeaters;
use A17\Twill\Repositories\ModuleRepository;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\Translation;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;
use Illuminate\Support\Facades\DB;

class TranslationGroupRepository extends ModuleRepository
{
    use HandleRepeaters;

    public function __construct(TranslationGroup $model)
    {
        $this->model = $model;
    }

    public function filter($query, array $scopes = [])
    {
        if (! empty($scopes['search'])) {
            $search = $scopes['search'];
            $delimiter = config('translation-handler.keyDelimiter', '.');
            $driver = DB::connection()->getDriverName();

            $concatKeyExpr = $driver === 'sqlite'
                ? "translation_keys.key LIKE (translation_groups.prefix || ? || '%')"
                : "translation_keys.key LIKE CONCAT(translation_groups.prefix, ?, '%')";

            $query->where(function ($q) use ($search, $delimiter, $concatKeyExpr) {
                $q->where('prefix', 'like', "%{$search}%")
                    ->orWhereExists(function ($sub) use ($search, $delimiter, $concatKeyExpr) {
                        $sub->select(DB::raw(1))
                            ->from('translation_keys')
                            ->whereRaw($concatKeyExpr, [$delimiter])
                            ->where('translation_keys.key', 'like', "%{$search}%");
                    })
                    ->orWhereExists(function ($sub) use ($search, $delimiter, $concatKeyExpr) {
                        $sub->select(DB::raw(1))
                            ->from('translation_values')
                            ->join('translation_keys', 'translation_keys.id', '=', 'translation_values.translation_key_id')
                            ->whereRaw($concatKeyExpr, [$delimiter])
                            ->where('translation_values.value', 'like', "%{$search}%");
                    });
            });
            unset($scopes['search']);
        }

        return parent::filter($query, $scopes);
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);

        $fields = $this->getFormFieldsForRepeater(
            $object,
            $fields,
            'translation_items',
            /** @phpstan-ignore argument.type */
            TranslationRepository::class,
            'translation_item'
        );

        return $fields;
    }

    public function update($id, $fields)
    {
        $this->syncTranslationValues($fields);

        unset($fields['repeaters'], $fields['blocks']);

        return parent::update($id, $fields);
    }

    protected function syncTranslationValues(array $fields): void
    {
        $repeaterItems = $fields['repeaters']['translation_item'] ?? [];
        $locales = config('translation-handler.locales', ['en']);

        foreach ($repeaterItems as $item) {
            $key = $item['key'] ?? null;

            if (! $key) {
                continue;
            }

            $translation = Translation::where('key', $key)->first();

            if (! $translation) {
                continue;
            }

            foreach ($locales as $locale) {
                $value = $item['value'][$locale] ?? '';

                $translation->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['value' => $value, 'active' => true]
                );
            }
        }
    }
}
