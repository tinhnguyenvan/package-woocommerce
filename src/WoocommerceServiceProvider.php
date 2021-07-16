<?php

namespace TinhPHP\Woocommerce;

use App\Models\Plugin;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use TinhPHP\Woocommerce\Console\InstallWoocommercePackage;
use Illuminate\Routing\Router;
use TinhPHP\Woocommerce\Middleware\SettingMiddleware;

class WoocommerceServiceProvider extends ServiceProvider
{
    public $pluginName = 'woocommerce';

    public function boot()
    {
        // check enable and disable plugin
        if ($this->plugin() != Plugin::STASTUS_ACTIVE) {
            return null;
        }

        $this->commands(
            [
            ]
        );

        // config
        if ($this->app->runningInConsole()) {
            // load migration
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

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
//            $this->publishes(
//                [
//                    __DIR__ . '/../database/migrations' => database_path('migrations'),
//                ],
//                'migrations'
//            );

            $this->publishes(
                [
                    __DIR__ . '/../resources/assets' => public_path('package_woocommerce'),
                ],
                'assets'
            );
        }

        // route middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('woo_setting', SettingMiddleware::class);

        // route
        // $this->registerRoutes();
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');

        // view
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'view_woocommerce');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lang_woocommerce');
    }

    public function plugin()
    {
        $status = false;
        try {
            // check enable and disable plugin
            $plugin = Plugin::query()->where('code', $this->pluginName)->first();
            if (!empty($plugin->id)) {
                $status = $plugin->status;
            }
        } catch (\Exception $exception) {
        }

        return $status;
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
