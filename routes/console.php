<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('build {project}', function ($project) {
    $this->info("Build {$project}!");
    // The Closure is bound to the underlying command instance,
    // so you have full access to all of the helper methods you
    // would typically be able to access on a full command class.


})
    // When defining a Closure based command, you may use the describe method to add a description to the command.
    // This description will be displayed when you run the php artisan list or php artisan help commands:
    ->describe("Build the custom project");
// Type-Hinting Dependencies
//In addition to receiving your command's arguments and options,
// command Closures may also type-hint additional dependencies
// that you would like resolved out of the service container:

//Artisan::command('email:send {user}', function (DripEmailer $drip, $user) {
//    $drip->send(User::find($user));
//});

