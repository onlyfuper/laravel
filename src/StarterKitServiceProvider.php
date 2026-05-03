<?php

namespace Local\StarterKit;

use Illuminate\Support\ServiceProvider;
use Local\StarterKit\Console\InstallCommand;
use Local\StarterKit\Console\SpriteGenerateCommand;
use Local\StarterKit\Console\UpdateCommand;

class StarterKitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                UpdateCommand::class,
                SpriteGenerateCommand::class,
            ]);
        }
    }
}
