<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\Translation;

class TranslationGroupRequest extends Request
{
    public function rulesForCreate()
    {
        return [];
    }

    public function rulesForUpdate()
    {
        if ($this->boolean('allow_empty')) {
            return [];
        }

        $rules = [];
        foreach ($this->keys() as $key) {
            if (str_starts_with($key, 'trans_') && is_array($this->input($key))) {
                foreach (array_keys($this->input($key)) as $locale) {
                    $rules["{$key}.{$locale}"] = 'required|string';
                }
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        $ids = [];
        foreach ($this->keys() as $key) {
            if (str_starts_with($key, 'trans_')) {
                $ids[] = (int) substr($key, 6);
            }
        }

        if (! empty($ids)) {
            Translation::whereIn('id', $ids)->pluck('key', 'id')->each(function ($translationKey, $id) use (&$attributes) {
                foreach (array_keys($this->input('trans_'.$id, [])) as $locale) {
                    $attributes["trans_{$id}.{$locale}"] = "{$translationKey} ({$locale})";
                }
            });
        }

        return $attributes;
    }
}
