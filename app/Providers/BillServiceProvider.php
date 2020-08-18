<?php

namespace App\Providers;

use App\Contract\BillerInterface;
use App\Contract\BillingNotifierInterface;
use App\Modules\BillingNotifiers\EmailBillingNotifier;
use App\Modules\StripeBiller;
use Illuminate\Support\ServiceProvider;

class BillServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BillerInterface::class, function ($app) {
            return new StripeBiller($app->make(BillingNotifierInterface::class));
        });
        $this->app->bind(BillingNotifierInterface::class, function ($app) {
            return new EmailBillingNotifier();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
