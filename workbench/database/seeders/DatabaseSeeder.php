<?php

namespace Workbench\Database\Seeders;

use A17\Twill\Models\User;
use BrunosCode\TranslationHandler\Collections\TranslationCollection;
use BrunosCode\TranslationHandler\Data\Translation;
use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\Facades\TranslationHandler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $email = 'test@test.test';
        $password = 'test@test.test';

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'role' => 'SUPERADMIN',
            'published' => true,
        ]);

        $user->password = Hash::make($password);
        $user->save();

        $this->seedTranslations();
    }

    protected function seedTranslations(): void
    {
        $translations = new TranslationCollection([
            new Translation('test.menu.home', 'en', 'Home'),
            new Translation('test.menu.home', 'it', 'Casa'),
            new Translation('test.menu.about', 'en', 'About'),
            new Translation('test.menu.about', 'it', 'Chi siamo'),
            new Translation('test.menu.contact', 'en', 'Contact'),
            new Translation('test.menu.contact', 'it', 'Contatti'),
            new Translation('test.footer.copyright', 'en', 'All rights reserved'),
            new Translation('test.footer.copyright', 'it', 'Tutti i diritti riservati'),
        ]);

        TranslationHandler::set($translations, TranslationOptions::DB, null, true);
    }
}
