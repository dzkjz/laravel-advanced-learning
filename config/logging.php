<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [ // the stack driver allows you to combine multiple channels into a single log channel
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            // The tap array should contain a list of classes that should have
            // an opportunity to customize (or "tap" into) the Monolog instance after it is created:
            'tap' => [App\Logging\CustomizeFormatter::class],
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
        // If you would like to define an entirely custom channel in which
        // you have full control over Monolog's instantiation and configuration,
        // you may specify a custom driver type in your config/logging.php configuration file.
        // Your configuration should include a via option to point to the factory
        // class which will be invoked to create the Monolog instance:
        'custom' => [
            'driver' => 'custom',
            'via' => App\Logging\CreateCustomLogger::class,
        ],
        'logentries' => [
            // Monolog has a variety of available handlers.
            // In some cases,
            // the type of logger you wish to create is merely a Monolog driver with an instance of a specific handler.
            // These channels can be created using the monolog driver.
            'driver' => 'monolog',
            // When using the monolog driver,
            // the handler configuration option is used to specify which handler will be instantiated.
            'handler' => Monolog\Handler\SyslogUdpHandler::class,
            // Optionally, any constructor parameters the handler needs may be specified using the with configuration option:
            'with' => [
                'host' => 'my.logentries.internal.datahubhost.company.com',
                'port' => '10000',
            ]
        ],
        'browser' => [
            'driver' => 'monolog',
            // When using the monolog driver, the Monolog LineFormatter will be used as the default formatter.
            'handler' => Monolog\Handler\BrowserConsoleHandler::class,
            // However, you may customize the type of formatter passed to the handler
            // using the formatter and formatter_with configuration options:
            'formatter' => Monolog\Formatter\HtmlFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'Y-m-d',
            ]
        ],
        'newrelic' => [
            'driver' => 'monolog',
            // If you are using a Monolog handler that is capable of providing its own formatter,
            'handler' => Monolog\Handler\NewRelicHandler::class,
            // you may set the value of the formatter configuration option to default:
            'formatter' => 'default',
        ]
    ],

];
