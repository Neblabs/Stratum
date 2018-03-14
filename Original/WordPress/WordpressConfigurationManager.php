<?php 

namespace Stratum\Original\WordPress;


Class WordpressConfigurationManager
{
    protected $defaultPrefix = 'wp_';

    protected static $configurationFile;
    protected static $tablePrefix;
    protected static $databaseConfigurationData;

    public function __construct()
    {
        if (defined('ABSPATH')) {
            //require_once ABSPATH . 'wp-config.php';

            if (static::$configurationFile == null) {
                static::$configurationFile = file_get_contents(ABSPATH . '/wp-config.php');
            }
        }

        
    }

    public function databaseConfigurationData()
    {
        if (static::$databaseConfigurationData === null) {
            (array) $wordPressDatabaseConfigurationData = [
                'name' => $this->extract('name'),
                'host' => $this->extract('host'),
                'username' => $this->extract('user'),
                'password' => $this->extract('password')
            ];

            static::$databaseConfigurationData = $wordPressDatabaseConfigurationData;
        }

        return static::$databaseConfigurationData;
    }

    public function tablePrefix()
    {
        if (!defined('ABSPATH')) {
            return $this->defaultPrefix;
        }


        if (static::$tablePrefix != null) {
            return static::$tablePrefix;
        }

        (string) $configurationFile = &static::$configurationFile;

        (integer) $tablePrefixVariablePosition = strpos($configurationFile, '$table_prefix');
        (integer) $stringStartPosiiton = strpos($configurationFile, "'", $tablePrefixVariablePosition);
        (integer) $stringEndPosition = strpos($configurationFile, "'", $stringStartPosiiton + 1);
        (integer) $stringLength = $stringEndPosition - $stringStartPosiiton;

        static::$tablePrefix = trim(substr($configurationFile, $stringStartPosiiton, $stringLength), "'");

        return static::$tablePrefix;
        
    }


    protected function extract($constant)
    {
        (string) $constantName = 'DB_' . strtoupper($constant);
        (string) $constanNameInString = "{$constantName}',";
        (string) $configurationFile = &static::$configurationFile;

        if (defined($constantName)) {
            return constant($constantName);
        }


        (integer) $constantNamePosition = strpos($configurationFile, $constanNameInString);
        (integer) $stringStartPosiiton = strpos($configurationFile, "'", $constantNamePosition + strlen($constanNameInString));
        (integer) $stringEndPosition = strpos($configurationFile, "'", $stringStartPosiiton + 1);
        (integer) $valueLength = $stringEndPosition - $stringStartPosiiton;

        (string) $value = trim(substr($configurationFile, $stringStartPosiiton, $valueLength), "'");

        return $value;

    }
}






