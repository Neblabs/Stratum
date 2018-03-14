<?php

namespace Stratum\Original\Test\Data\TestClass;

use Stratum\Original\Data\GetterAndSetter;

Class ConcreteGetterAndSetter extends GetterAndSetter
{
    public function getTitle()
    {
        return 'filtered title';
    }
}