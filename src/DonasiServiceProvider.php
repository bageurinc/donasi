<?php

namespace Bageur\Donasi;

use Illuminate\Support\ServiceProvider;

class DonasiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Bageur\Donasi\CampaignController');
        $this->app->make('Bageur\Donasi\LembagaController');
        $this->app->make('Bageur\Donasi\DonaturController');
        $this->app->make('Bageur\Donasi\MembersController');
        $this->app->make('Bageur\Donasi\AktifitasController');
        $this->app->make('Bageur\Donasi\PenerimaController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/migration');
    }
}
