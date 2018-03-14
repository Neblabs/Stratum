<?php

namespace Stratum\Custom\Validator;

use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Request\Validator;

Class ConcreteValidatorTest55 extends Validator
{
    public function passingValidator(Request $request)
    {
        $this->passed();
    }

    public function failingValidator(Dispatcher $use)
    {
        $this->failed();

        return $use->controller('controller.name');
    }

    public function nullValidator()
    {
        // null
    }

    public function wrongFailingValidator()
    {
        $this->failed();

        return new \stdClass;
    }
}