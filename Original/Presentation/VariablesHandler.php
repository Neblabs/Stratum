<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Presentation\Exception\UnbindedVariableException;

Class VariablesHandler
{
    public function __get($property)
    {
        throw new UnbindedVariableException(
            "Cannot read unbinded variable: $property"
        );
    }
}