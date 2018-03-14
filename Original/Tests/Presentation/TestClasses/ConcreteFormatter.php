<?php

namespace Stratum\Original\Test\Presentation\TestClass;

use Stratum\Original\Presentation\Formatter;

Class ConcreteFormatter extends Formatter
{
    public function inUpperCase()
    {
        return $this->text;
    }
}