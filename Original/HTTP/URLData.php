<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class URLData
{
    use ClassName;
    
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }
}