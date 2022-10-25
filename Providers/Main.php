<?php

namespace Modules\Gerencianet\Providers;

use Illuminate\Support\Facades\View;
// use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as Provider;

class Main extends Provider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();
        $this->loadTranslations();
        // $this->loadConfig();
        $this->loadViews();
        $this->loadViewComposers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutes();
    }

    /**
     * Load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'gerencianet');
    }

    /**
     * Load migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'gerencianet');
    }

    /**
     * Load config.
     *
     * @return void
     */
    // public function loadConfig()
    // {
    //     Config::set('search-string', array_merge_recursive(
    //         Config::get('search-string'),
    //         require __DIR__ . '/../Config/search-string.php'
    //     ));
    // }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $routes = [
            'admin.php',
            'portal.php',
            'guest.php',
            'signed.php',
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }

    /**
     * Load view composers
     *
     * @return void
     */
    public function loadViewComposers()
    {
        View::composer(
            [
                'sales.invoices.create',
                'sales.invoices.edit',
                'sales.customers.create',
                'sales.customers.edit'
            ],
            function ($view) {
                $field_validations = setting('gerencianet.field_validations', '0');

                if($field_validations === '1') {
                    $view->getFactory()->startPrepend(
                        'scripts_end',
                        view('gerencianet::_script')
                    );
                }
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
