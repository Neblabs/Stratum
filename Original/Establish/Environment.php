<?php

namespace Stratum\Original\Establish;


Class Environment
{
    protected static $environment = [];

    public static function is()
    {
        return new Static;
    }

    public static function setTemporaryEnvironment($environment)
    {
        static::$environment['environment'] = $environment;
    }

    public function __construct()
    {
        if (empty(static::$environment)) {
            static::$environment = $this->loadEnvironment();
        }
    }

    public function production()
    {
        return static::$environment['environment'] === 'Production';
    }

    public function development()
    {
        return static::$environment['environment'] === 'Development';
    }

    public function testing()
    {
        return static::$environment['environment'] === 'Testing';
    }

    protected function loadEnvironment()
    {
        return require STRATUM_ROOT_DIRECTORY . '/Establish/Environment.php';
    }

    public static function className()
    {
        return get_class();
    }
}