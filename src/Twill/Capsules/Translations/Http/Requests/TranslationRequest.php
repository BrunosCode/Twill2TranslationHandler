<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Http\Requests;

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
        $valueRule = $this->boolean('allow_empty') ? 'nullable|string' : 'required|string';

        return $this->rulesForTranslatedFields([], [
            'value' => $valueRule,
        ]);
    }
}
