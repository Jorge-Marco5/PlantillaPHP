<?php

declare(strict_types=1);

namespace App\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

class Logger
{
    private static ?MonologLogger $logger = null;

    public static function get(): MonologLogger
    {
        if (self::$logger === null) {
            self::$logger = new MonologLogger('app');
            // Log to var/log/app.log
            $logFile = __DIR__ . '/../../var/log/app.log';

            // Ensure directory exists
            if (!is_dir(dirname($logFile))) {
                mkdir(dirname($logFile), 0777, true);
            }

            self::$logger->pushHandler(new StreamHandler($logFile, Level::Debug));
        }

        return self::$logger;
    }
}