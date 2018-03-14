<?php

namespace Stratum\Original\Test\Data\TestClass;

use Stratum\Original\Data\Domain;

Class ConcreteDomain extends Domain
{
    protected function getAuthorName()
    {
        return 'Edited Author by Domain';
    }

    protected function getYear()
    {
        return 'Domain took precedence: year';
    }

    public function operateOnData()
    {
        //
    }

    public function operateOnDataWithArguments($argument)
    {
        //
    }
}