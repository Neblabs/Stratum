<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Exception\UnbindedVariableException;
use Stratum\Original\Presentation\PartialView;
use Stratum\Original\Presentation\VariablesHandler;

Class VariablesHandlerTest extends TestCase
{
    public function test_throws_exception_when_reading_undefined_property()
    {
        $this->expectException(UnbindedVariableException::class);
        $this->expectExceptionMessage('Cannot read unbinded variable: posts');

        $VariablesHandler = new VariablesHandler;

        $VariablesHandler->posts;
    }
}

