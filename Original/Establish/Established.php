<?php

namespace Stratum\Original\Establish;

Class Established
{
    public static function database()
    {
        return require STRATUM_ROOT_DIRECTORY . '/Establish/Database.php';
    }
}