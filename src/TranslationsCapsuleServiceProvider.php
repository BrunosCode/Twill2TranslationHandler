<?php

namespace BrunosCode\Twill2TranslationHandler;

use A17\Twill\TwillPackageServiceProvider;

class TranslationsCapsuleServiceProvider extends TwillPackageServiceProvider
{
    /** @var bool Automatically scan and register all capsules in src/Twill/Capsules/ */
    protected $autoRegisterCapsules = true;

    public function boot(): void
    {
        parent::boot();
    }
}
