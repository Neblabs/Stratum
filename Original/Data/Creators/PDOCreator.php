<?php

namespace Stratum\Original\Data\Creator;

use PDO;
use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Original\Establish\Established;

Class PDOCreator
{
    protected static $PDO;
    protected $forceNewInstance = false;

    public function create()
    {
        if (is_null(static::$PDO) || $this->forceNewInstance) {
            return $this->newPDO();
        }

        return static::$PDO;
    }

    public function forceNewInstance($forceNewInstance)
    {
        $this->forceNewInstance = $forceNewInstance;
    }

    public function createNew()
    {
        (object) $database = Established::database();
        (string) $characterSet = defined('DB_CHARSET')? DB_CHARSET : 'utf8';

        return new PDO(
            "mysql:host={$database->host};dbname={$database->name};charset={$characterSet}",
            $database->username,
            $database->password
        );
    }

    protected function newPDO()
    {
        static::$PDO = null;
        
        static::$PDO = $this->createNew();

        return static::$PDO;
    }


}