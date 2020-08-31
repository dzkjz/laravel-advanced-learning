<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

use League\Flysystem\Filesystem;

class DropboxServiceProvider extends ServiceProvider
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

        Storage::extend('dropbox', function ($app, $config) {

            $client = new DropboxClient(
                $config['authorization_token']//authorization_token定义在config/filesystems.php文件中
            );
            return new Filesystem(new DropboxAdapter($client));
        });
    }
}
