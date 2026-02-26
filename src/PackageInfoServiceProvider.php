<?php

namespace Codingwithrk\PackageInfo;

use Illuminate\Support\ServiceProvider;
use Codingwithrk\PackageInfo\Commands\CopyAssetsCommand;

class PackageInfoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PackageInfo::class, function () {
            return new PackageInfo();
        });
    }

    public function boot(): void
    {
        // Register plugin hook commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CopyAssetsCommand::class,
            ]);
        }
    }
}