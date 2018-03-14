<?php

namespace Stratum\Extend\Finder\MYSQL;

use Stratum\Original\WordPress\WordpressConfigurationManager;

Class Wordpress extends MYSQL
{
    protected static $wordpressConfigurationManager;

    public function tablePrefix()
    {
        if (static::$wordpressConfigurationManager == null) {
            static::$wordpressConfigurationManager = new WordpressConfigurationManager;
        }

        return static::$wordpressConfigurationManager->tablePrefix();
    }
}