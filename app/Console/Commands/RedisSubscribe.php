<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Redis::subscribe(['test-channel'], function ($message) {
            echo $message;
        });

        // Using the psubscribe method, you may subscribe to a wildcard channel,
        // which may be useful for catching all messages on all channels.
        // The $channel name will be passed as the second argument to the provided callback Closure:
        Redis::psubscribe(['*'], function ($message, $channel) {
            echo $message;
        });

        Redis::psubscribe(['users.*'], function ($message, $channel) {
            echo $message;
        });
    }
}