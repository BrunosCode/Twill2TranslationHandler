<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;

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
}
