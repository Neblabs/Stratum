<?php

namespace Stratum\Extend\Counter\MYSQL;

use Stratum\Original\Data\Finder;

Class Count 
{

    private $finder;

    private function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public static function __callStatic($method, $arguments)
    {
        $method = ucfirst($method);
        (string) $finder = "Stratum\\Custom\\Finder\\MYSQL\\$method";

        $finder = new $finder;

        $finder->columns = 'count(*) as count';

        return new Self($finder);

    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->finder, $method], $arguments);
    }

    public function find()
    {
        return (integer) $this->finder->find()->first()->count;
    }
}