<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespace to assume when generating URLs to your application.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configurations.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for your application.
     *
     * @return void
     */
    public function map()
    {
//        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapDomainRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateful.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapDomainRoutes()
    {
        $domainPath = app_path('Domains');
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($domainPath)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'api.php') {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($file->getPathname());
            }
            if ($file->isFile() && $file->getFilename() === 'web.php') {
                Route::middleware('web')
                    ->group($file->getPathname());
            }
        }
    }
}
