<?php
namespace App\Libs;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log {

    const NAME_USER  = 'user';
    const NAME_STORE = 'store';

    const LEVEL_ERROR = 'error';
    const LEVEL_INFO = 'info';
    const LEVEL_EMERGENCY = 'emergency';

    private static $logInstances = array();

    public static function info($name, $message, $context = [])
    {
        return static::getInstances($name, static::LEVEL_INFO)->info($message, $context);
    }

    public static function error($name, $message, $context = [])
    {
        return static::getInstances($name, static::LEVEL_ERROR)->error($message, $context);
    }

    public static function emergency($name, $message, $context = [])
    {
        return static::getInstances($name, static::LEVEL_EMERGENCY)->emerg($message, $context);
    }

    public static function exception($exception, $name = '') {
        $msg = 'file:' . $exception->getFile() . ' line: ' . $exception->getLine()  . ' message: ' . $exception->getMessage();
        return static::emergency($name . '_exception', $msg);
    }

    public static function getInstances($name, $level)
    {
        $key = $name . $level;
        if (!empty(static::$logInstances[$key])) {
            return static::$logInstances[$key];
        }

        $logObj = new Logger($name);
        $day  = date('Ymd');
        $logFile = storage_path() . "/logs/{$day}.log";

        switch ($level) {
            case 'error':
                $logObj->pushHandler(new StreamHandler($logFile, Logger::ERROR));
                break;
            case 'emergency':
                $logObj->pushHandler(new StreamHandler($logFile, Logger::EMERGENCY));
                break;
            default:
                $logObj->pushHandler(new StreamHandler($logFile, Logger::INFO));
                break;
        }

        return static::$logInstances[$key] = $logObj;
    }
}