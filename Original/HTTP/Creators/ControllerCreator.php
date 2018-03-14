<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Creator\Creator;

Class ControllerCreator extends Creator
{
    protected function fullyQualifiedClassName()
    {
        return "Stratum\Custom\Controller\\$this->className";
    }
}