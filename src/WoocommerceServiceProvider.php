<?php

namespace TinhPHP\Woocommerce;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use TinhPHP\Woocommerce\Console\InstallWoocommercePackage;

class WoocommerceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands(
            [
            ]
        );

        // config
        if ($this->app->runningInConsole()) {
            // install package
            $this->commands(
                [
                    InstallWoocommercePackage::class,
                ]
            );

            // public config
            $this->publishes(
                [
                    __DIR__ . '/../config/config.php' => config_path('config_package_woocommerce.php'),
                ],
                'config'
            );

            // public migrations
            $this->publishes(
                [
                    __DIR__ . '/../database/migrations' => database_path('migrations'),
                ],
                'migrations'
            );

            $this->publishes(
                [
                    __DIR__ . '/../resources/assets' => public_path('package_woocommerce'),
                ],
                'assets'
            );
        }

        // route middleware

        // route
        // $this->registerRoutes();
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');

        // view
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'view_woocommerce');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lang_woocommerce');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'config_package_woocommerce');
        $this->mergeConfigFrom(__DIR__ . '/../config/constant.php', 'constant');
    }

    protected function registerRoutes()
    {
        Route::group(
            $this->routeConfiguration(),
            function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
                $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
            }
        );
    }

    protected function routeConfiguration()
    {
        return [
            'middleware' => ['auth'],
        ];
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param $path
     * @param $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, $this->mergeConfig(require $path, $config));
    }

    /**
     * Merges the configs together and takes multi-dimensional arrays into account.
     *
     * @param array $original
     * @param array $merging
     * @return array
     */
    protected function mergeConfig(array $original, array $merging)
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            if (!Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }

        return $array;
    }
}