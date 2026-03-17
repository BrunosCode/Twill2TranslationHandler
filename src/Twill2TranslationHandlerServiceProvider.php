<?php

namespace BrunosCode\Twill2TranslationHandler;

use A17\Twill\Facades\TwillCapsules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Twill2TranslationHandlerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('twill-2-translation-handler')
            ->hasConfigFile('translation-handler')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    // ->startWith(function (InstallCommand $command) {
                    //     $command->info('Installing LaravelTranslationHandler first...');
                    //     $command->call('laraveltranslationhandler:install');
                    // })
                    ->publishConfigFile();
                // ->endWith(function (InstallCommand $command) {
                //     $command->info('Copying Twill2TranslationHandler Capsule...');
                //     $command->info(app_path('Twill/Capsules'));

                //     $vendorName = 'BrunosCode';
                //     $packageName = 'Twill2TranslationHandler';
                //     $destination = app_path('Twill/Capsules');
                //     $sourcePath = __DIR__ . '/Twill/Capsules';

                //     // Verify source path exists
                //     if (!File::exists($sourcePath)) {
                //         $command->error("Twill capsule not found at: $sourcePath");
                //         return Command::FAILURE;
                //     }

                //     // Copy files to the destination
                //     File::copyDirectory($sourcePath, $destination);
                //     $command->info("Copied to: $destination");

                //     // Update namespace in files
                //     $oldNamespace = ucfirst($vendorName) . '\\' . ucfirst($packageName);
                //     $newNamespace = 'App\\' . str_replace('/', '\\', $destination) . '\\' . ucfirst($packageName);

                //     $files = File::allFiles($destination);

                //     foreach ($files as $file) {
                //         if ($file->getExtension() === 'php') {
                //             $contents = File::get($file->getPathname());
                //             $updatedContents = str_replace("namespace $oldNamespace", "namespace $newNamespace", $contents);

                //             File::put($file->getPathname(), $updatedContents);
                //         }
                //     }

                //     $command->info('Namespaces updated successfully.');

                //     // Update Composer autoload
                //     $command->info('Updating Composer autoload...');
                //     exec('composer dump-autoload');

                //     $command->info('Twill Capsule copied and namespace updated successfully.');
                //     return Command::SUCCESS;
                // });
            });
    }

    public function packageBooted(): void
    {
        // TwillCapsules::registerPackageCapsule(
        //     'Translations',
        //     'BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations',
        //     __DIR__ . '/Twill/Capsules/Translations',
        //     true,
        //     false
        // );
        // TwillCapsules::registerPackageCapsule(
        //     'TranslationGroups',
        //     'BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups',
        //     __DIR__ . '/Twill/Capsules/TranslationGroups',
        //     true,
        //     false
        // );
        parent::packageBooted();
    }
}
