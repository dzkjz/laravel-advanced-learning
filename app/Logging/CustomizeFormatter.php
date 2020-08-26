<?php


namespace App\Logging;


use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    /**
     * Once you have configured the tap option on your channel,
     * you're ready to define the class that will customize your Monolog instance.
     * This class only needs a single method: __invoke,
     * which receives an Illuminate\Log\Logger instance.
     * The Illuminate\Log\Logger instance proxies all method calls to the underlying Monolog instance:
     * @param $logger
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                '[%datetime%] %channel%.%level_name%: %message% %context% %extra%'
            ));
        }
    }
}
