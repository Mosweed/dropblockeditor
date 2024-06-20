<?php

namespace Mo_sweed\DropBlockEditor;

use App\Livewire\DropBlockEditor\Buttons\SaveButten;
use App\Livewire\DropBlockEditor\Components\Example;
use App\Livewire\DropBlockEditor\DropBlockEditor;
use App\Livewire\DropBlockEditor\PageEiditor;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Mo_sweed\DropBlockEditor\Commands\MakeBlockCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class DropBlockEditorServiceProvider extends PackageServiceProvider
{



    public function configurePackage(Package $package): void
    {
        $package
            ->name('dropblockeditor')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(MakeBlockCommand::class);




    }




    public function bootingPackage(): void
    {

        Livewire::component('dropblockeditor', DropBlockEditor::class);
        Livewire::component('dropblockeditor-example', Example::class);


        View::composer('dropblockeditor::editor', function ($view) {
            if (config('dropblockeditor.include_js', true)) {
                $view->jsPath = __DIR__ . '/../public/editor.js';
            }

            if (config('dropblockeditor.include_css', true)) {
                $view->cssPath = __DIR__ . '/../public/editor.css';
            }
        });

        $this->publishes([
            __DIR__ . '/Models/pages.php' => app_path('Models/pages.php'),
            __DIR__ . '/migrations/2024_03_18_090329_create_pages_table.php' => database_path('migrations/2024_03_18_090329_create_pages_table.php'),
        ], 'dropblockeditor-database');

        $this->publishes([
            __DIR__ . '/Blocks/Block.php' => app_path('Classes/Block.php'),
            __DIR__ . '/Blocks/Example.php' => app_path('Classes/Blocks/Example.php'),
            __DIR__ . '/Components/BlockEditComponent.php' => app_path('Livewire/DropBlockEditor/BlockEditComponent.php'),
            __DIR__ . '/Components/DropBlockEditor.php' => app_path('Livewire/DropBlockEditor/DropBlockEditor.php'),
            __DIR__ . '/Components/Example.php' => app_path('Livewire/DropBlockEditor/Components/Example.php'),
            __DIR__ . '/Components/PageEiditor.php' => app_path('Livewire/DropBlockEditor/PageEiditor.php'),
        ], 'dropblockeditor-blocks');

        $this->publishes([
                    __DIR__.'/views' => resource_path('views/livewire/dropblockeditor'),
                ], 'dropblockeditor-views');

        $this->publishes([
            __DIR__ . '/../routes/dropblockeditor.php' => base_path('routes/dropblockeditor.php'),

                    ], 'dropblockeditor-routes');






    }

}