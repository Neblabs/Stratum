<?php

namespace Stratum\Original\Data;

use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Domain extends GetterAndSetter
{
    use ClassName;

    protected $data;

    public function __construct(Data $data)
    {

        $this->data = $data;
    }
}