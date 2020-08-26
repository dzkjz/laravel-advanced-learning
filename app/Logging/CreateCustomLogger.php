<?php


namespace App\Logging;


use Monolog\Logger;

class CreateCustomLogger
{
    public function __invoke(array $config)
    {
        return new Logger(
            'custom'
        );
    }
}
