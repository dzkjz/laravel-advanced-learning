<?php

namespace App\Console;

use App\Console\Commands\SendEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // You may also manually register commands by adding its class name to the $commands property
        // of your app/Console/Kernel.php file.
        // When Artisan boots, all the commands listed in this property
        // will be resolved by the service container and registered with Artisan:

        SendEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        // Because of the load method call in your console kernel's commands method,
        // all commands within the app/Console/Commands directory will automatically be registered with Artisan.
        // In fact,
        // you are free to make additional calls to the load method to scan other directories for Artisan commands:
        $this->load(__DIR__ . '/MoreCommands');

        require base_path('routes/console.php');
    }
}
