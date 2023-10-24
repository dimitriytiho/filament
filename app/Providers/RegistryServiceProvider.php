<?php

namespace App\Providers;

use App\Helpers\ParamHelper;
use App\Services\Registry\Repository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDO;

class RegistryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Зарегистрировать свой класс в провайдере
        /*$this->app->singleton(Registry::class, function ($app) {
            return new Registry();
        });*/
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Зарегистрировать в провайдере
        $this->app->singleton('registry', Repository::class);

        // Добавляем данные
        /*if (DB::connection()->getPdo()->getAttribute(PDO::ATTR_CONNECTION_STATUS)) {
            $registry = app('registry');
            if (Schema::hasTable('params')) {
                $registry->set('params', cache()->remember('params_all', 600, fn () => Param::all()));
            }
        }*/
    }
}
