<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Creator\Creator;

Class ValidatorCreator extends Creator
{
    protected function fullyQualifiedClassName()
    {
        return "Stratum\Custom\Validator\\$this->className";
    }
}