<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;

class TranslationRequest extends Request
{
    public function rulesForCreate()
    {
        return [
            'key' => 'required|string|unique:translation_keys,key',
        ];
    }

    public function rulesForUpdate()
    {
        $locales = config('translation-handler.locales', ['en']);

        $rules = [];
        foreach ($locales as $locale) {
            $rules["translations.{$locale}.value"] = 'required|string';
        }

        return $rules;
    }
}
