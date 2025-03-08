<?php

namespace App\Providers;

use App\Utils\CodeGenerator\CodeGenerator;
use App\Utils\CodeGenerator\ICodeGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ICodeGenerator::class, CodeGenerator::class);
        $this->app->singleton(IFrontAdsService::class, FrontAdsService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerObservers();
    }

    private function registerObservers(): void
    {
        $path = app_path("Observers");
        $files = app("files")->files($path);
        foreach ($files as $file) {
            $observer = str_replace(".php", "", $file->getFileName());
            $namespace = "App\\Observers\\";
            $model = "App\\Models\\" . str_replace("Observer", "", $observer);
            $model::observe($namespace . $observer);
        }
    }
}
