<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // Now that we have registered the composer, the ProfileComposer@compose method will be executed each time the profile view is being rendered.
        View::composer('profile', 'App\Http\View\Composers\ProfileComposer');

        View::composer('dashboard', function ($view) {

            //
        });

        View::composer(
            ['profile', 'dashboard'],
            'App\Http\View\Composers\ProfileComposer'
        );
        // The composer method also accepts the * character as a wildcard, allowing you to attach a composer to all views:
        View::composer('*', function ($view) {
            //
        });

        // View creators are very similar to view composers; however,
        // they are executed immediately after the view is instantiated instead of waiting until the view is about to render.
        // To register a view creator, use the creator method:
        View::creator('profle', 'App\Http\View\Composers\ProfileComposer');

    }
}
